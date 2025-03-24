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


    // VERIFY EMAIL
    // VERIFY EMAIL
    public function verifyEmail(Request $request)
    {
        $user = User::where('verification_token', $request->token)->first();

        if (!$user) {
            return ResponseHelper::error(message: 'Token tidak valid atau sudah digunakan.', statuscode: 400);
        }

        // Pastikan update berjalan
        $user->email_verified_at = now();
        $user->verification_token = null;

        if ($user->save()) {
            return ResponseHelper::success(message: 'Email berhasil diverifikasi. Anda sekarang bisa login.', statuscode: 200);
        } else {
            return ResponseHelper::error(message: 'Gagal memperbarui status verifikasi.', statuscode: 500);
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