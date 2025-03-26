<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Helper\ResponseHelper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $verificationToken = Str::random(64);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verification_token' => $verificationToken,
            ]);

            Log::info('User berhasil dibuat', ['user' => $user]);

            Mail::send('emails.verify', ['token' => $verificationToken], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Verifikasi Email Anda');
            });

            DB::commit();

            return ResponseHelper::success(
                message: 'Akun berhasil dibuat. Silakan cek email untuk verifikasi.',
                statuscode: 201
            );
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Gagal register user: ' . $e->getMessage() . ' - line ' . $e->getLine());
            return ResponseHelper::error(message: 'Terjadi kesalahan, coba lagi.', statuscode: 500);
        }
    }
    public function verifyEmail(Request $request)
    {
        try {
            $user = User::where('verification_token', $request->token)->first();

            if (!$user) {
                // Redirect to frontend with error parameter
                return redirect()->away(
                    config('app.frontend_url') . '/auth?verification=failed&message=Token+tidak+valid'
                );
            }

            // Update user status
            $user->email_verified_at = now();
            $user->verification_token = null;
            $user->save();

            // Redirect to frontend with success parameter
            return redirect()->away(
                config('app.frontend_url') . '/auth?verification=success'
            );
        } catch (\Exception $e) {
            Log::error('Email verification failed: ' . $e->getMessage());

            // Redirect to frontend with error
            return redirect()->away(
                config('app.frontend_url') . '/auth?verification=failed&message=Terjadi+kesalahan'
            );
        }
    }

    /**
     * Resend verification email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function resendVerification(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error(
                message: 'Email tidak valid atau tidak terdaftar.',
                data: $validator->errors(),
                statuscode: 422
            );
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();

        // Check if user exists
        if (!$user) {
            return ResponseHelper::error(
                message: 'Email tidak terdaftar.',
                statuscode: 404
            );
        }

        // Check if email is already verified
        if ($user->email_verified_at !== null) {
            return ResponseHelper::error(
                message: 'Email ini sudah diverifikasi. Silakan login.',
                statuscode: 400
            );
        }

        // Check if last verification email was sent within the last minute
        // You can store this in the database or use a cache system
        // For this example, we'll use a simple session-based approach
        $lastSent = $request->session()->get('last_verification_sent_' . $user->id);
        if ($lastSent && now()->diffInSeconds(\Carbon\Carbon::parse($lastSent)) < 60) {
            $timeLeft = 60 - now()->diffInSeconds(\Carbon\Carbon::parse($lastSent));
            return ResponseHelper::error(
                message: "Harap tunggu {$timeLeft} detik sebelum mengirim email baru.",
                statuscode: 429
            );
        }

        try {
            // Generate new verification token
            $verificationToken = Str::random(64);
            $user->verification_token = $verificationToken;
            $user->save();

            // Send verification email
            Mail::send('emails.verify', ['token' => $verificationToken], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Verifikasi Email Anda');
            });

            // Store last sent timestamp
            $request->session()->put('last_verification_sent_' . $user->id, now());

            return ResponseHelper::success(
                message: 'Link verifikasi baru telah dikirim ke email Anda.',
                statuscode: 200
            );
        } catch (Exception $e) {
            Log::error('Gagal mengirim email verifikasi: ' . $e->getMessage() . ' - line ' . $e->getLine());

            return ResponseHelper::error(
                message: 'Terjadi kesalahan saat mengirim email verifikasi. Silakan coba lagi.',
                statuscode: 500
            );
        }
    }


    // LOGIN
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return ResponseHelper::error(message: 'Login gagal, kredensial tidak valid', statuscode: 400);
            }

            if (!$user->email_verified_at) {
                return ResponseHelper::error(message: 'Email belum diverifikasi. Silakan cek email Anda.', statuscode: 403);
            }

            $token = $user->createToken('My API Token')->plainTextToken;

            return ResponseHelper::success(message: 'Login berhasil', data: ['user' => $user, 'token' => $token], statuscode: 200);
        } catch (Exception $e) {
            Log::error('Gagal login user: ' . $e->getMessage() . ' - line ' . $e->getLine());
            return ResponseHelper::error(message: 'Terjadi kesalahan, coba lagi.', statuscode: 500);
        }
    }

    // GET USER PROFILE
    public function userProfile()
    {
        try {
            $user = Auth::user();
            return $user
                ? ResponseHelper::success(message: 'Berhasil mengambil data profile', data: $user, statuscode: 200)
                : ResponseHelper::error(message: 'Gagal mengambil data', statuscode: 400);
        } catch (Exception $e) {
            Log::error('Gagal mengambil data profil: ' . $e->getMessage() . ' - line ' . $e->getLine());
            return ResponseHelper::error(message: 'Terjadi kesalahan, coba lagi.', statuscode: 500);
        }
    }

    // LOGOUT
    public function userLogout()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->currentAccessToken()->delete();
                return ResponseHelper::success(message: 'Logout berhasil', statuscode: 200);
            }
            return ResponseHelper::error(message: 'Logout gagal', statuscode: 400);
        } catch (Exception $e) {
            Log::error('Gagal logout: ' . $e->getMessage() . ' - line ' . $e->getLine());
            return ResponseHelper::error(message: 'Terjadi kesalahan, coba lagi.', statuscode: 500);
        }
    }

    // SEND PASSWORD RESET LINK
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $token = Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        Mail::raw("Gunakan token ini untuk mereset password Anda: $token", function ($message) use ($request) {
            $message->to($request->email)->subject('Reset Password');
        });

        return ResponseHelper::success(message: 'Link reset password telah dikirim ke email.', statuscode: 200);
    }

    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $resetToken = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetToken || !Hash::check($request->token, $resetToken->token)) {
            return ResponseHelper::error(message: 'Token reset password tidak valid atau sudah kedaluwarsa.', statuscode: 400);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return ResponseHelper::success(message: 'Password berhasil direset.', statuscode: 200);
    }
}
