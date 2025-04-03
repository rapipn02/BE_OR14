<?php

namespace App\Http\Controllers;

use App\Models\UserVerifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserVerifikasiController extends Controller
{
    /**
     * Upload verification documents
     */
    public function uploadFiles(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'krs' => 'required|file|max:10240', // 10MB max
                'payment_proof' => 'required|file|max:10240',
                'neo_ig' => 'required|file|max:10240',
                'marketing_ig' => 'required|file|max:10240',
            ]);

            $user = Auth::user();

            // Check if user already has verification documents
            $verification = UserVerifikasi::where('user_id', $user->id)->first();

            if ($verification && in_array($verification->verification_status, ['diproses', 'disetujui'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki dokumen verifikasi yang sedang diproses atau telah disetujui'
                ], 400);
            }

            // Store files
            $krsPath = $request->file('krs')->store('public/verification');
            $paymentPath = $request->file('payment_proof')->store('public/verification');
            $neoIgPath = $request->file('neo_ig')->store('public/verification');
            $marketingIgPath = $request->file('marketing_ig')->store('public/verification');

            // If verification exists, update it, otherwise create new
            if ($verification) {
                // Remove old files if they exist
                if ($verification->krs_path && Storage::exists($verification->krs_path)) {
                    Storage::delete($verification->krs_path);
                }
                if ($verification->payment_proof_path && Storage::exists($verification->payment_proof_path)) {
                    Storage::delete($verification->payment_proof_path);
                }
                if ($verification->neo_path && Storage::exists($verification->neo_path)) {
                    Storage::delete($verification->neo_path);
                }
                if ($verification->marketing_path && Storage::exists($verification->marketing_path)) {
                    Storage::delete($verification->marketing_path);
                }

                // Update with new files
                $verification->update([
                    'krs_path' => $krsPath,
                    'payment_proof_path' => $paymentPath,
                    'neo_path' => $neoIgPath,
                    'marketing_path' => $marketingIgPath,
                    'verification_status' => 'diproses', // Reset to pending
                    'verified_at' => null,
                    'rejection_reason' => null
                ]);
            } else {
                // Create new verification record
                $verification = UserVerifikasi::create([
                    'user_id' => $user->id,
                    'krs_path' => $krsPath,
                    'payment_proof_path' => $paymentPath,
                    'neo_path' => $neoIgPath,
                    'marketing_path' => $marketingIgPath,
                    'verification_status' => 'diproses'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dokumen verifikasi berhasil diunggah',
                'data' => $verification
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading verification files: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah dokumen verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check verification status
     */
    public function checkVerificationStatus()
    {
        try {
            $user = Auth::user();
            $verification = UserVerifikasi::where('user_id', $user->id)->first();

            if (!$verification) {
                return response()->json([
                    'success' => true,
                    'data' => null // No verification yet
                ]);
            }

            // Generate full URLs for files
            $fileUrls = [
                'krs' => $verification->krs_path ? url(Storage::url($verification->krs_path)) : null,
                'payment' => $verification->payment_proof_path ? url(Storage::url($verification->payment_proof_path)) : null,
                'neo_ig' => $verification->neo_path ? url(Storage::url($verification->neo_path)) : null,
                'marketing_ig' => $verification->marketing_path ? url(Storage::url($verification->marketing_path)) : null
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $verification->id,
                    'status' => $verification->verification_status, // 'diproses', 'disetujui', or 'ditolak'
                    'files' => $fileUrls,
                    'verified_at' => $verification->verified_at,
                    'rejection_reason' => $verification->rejection_reason,
                    'created_at' => $verification->created_at,
                    'updated_at' => $verification->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking verification status: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }
}
