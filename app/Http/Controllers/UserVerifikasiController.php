<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserVerifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserVerifikasiController extends Controller
{
    /**
     * Upload verification documents
     */
    public function uploadFiles(Request $request)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'krs_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'payment_proof_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'neo_ig_file' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'marketing_ig_file' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $verification = UserVerifikasi::where('user_id', $user->id)->first();

        // If there's a previous verification and it was rejected, delete the old documents
        if ($verification && $verification->verification_status === 'ditolak') {
            if ($verification->krs_path) {
                Storage::disk('public')->delete($verification->krs_path);
            }
            if ($verification->payment_proof_path) {
                Storage::disk('public')->delete($verification->payment_proof_path);
            }
            if ($verification->neo_path) {
                Storage::disk('public')->delete($verification->neo_path);
            }
            if ($verification->marketing_path) {
                Storage::disk('public')->delete($verification->marketing_path);
            }
            $verification->delete();
            $verification = null;
        }

        // If the user already has a pending or verified verification, return an error
        if ($verification) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki pengajuan verifikasi dengan status ' . $verification->verification_status
            ], 400);
        }

        // Store the documents
        $krsPath = $request->file('krs_file')->store('verification_documents/krs', 'public');
        $paymentPath = $request->file('payment_proof_file')->store('verification_documents/payment', 'public');
        $neoPath = $request->file('neo_ig_file')->store('verification_documents/neo_ig', 'public');
        $marketingPath = $request->file('marketing_ig_file')->store('verification_documents/marketing_ig', 'public');

        // Create verification record
        $verification = UserVerifikasi::create([
            'user_id' => $user->id,
            'krs_path' => $krsPath,
            'payment_proof_path' => $paymentPath,
            'neo_path' => $neoPath,
            'marketing_path' => $marketingPath,
            'verification_status' => 'diproses',
            'rejection_reason' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen verifikasi berhasil diunggah',
            'data' => $verification
        ], 201);
    }

    /**
     * Check verification status
     */
    public function checkVerificationStatus()
    {
        $user = Auth::user();
        $verification = UserVerifikasi::where('user_id', $user->id)->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada pengajuan verifikasi',
                'data' => null
            ]);
        }

        // Get base URL from config
        $baseUrl = config('app.url');

        return response()->json([
            'success' => true,
            'message' => 'Status verifikasi berhasil diambil',
            'data' => [
                'status' => $verification->verification_status,
                'rejection_reason' => $verification->rejection_reason,
                'files' => [
                    'krs' => $verification->krs_path ? $baseUrl . '/storage/' . $verification->krs_path : null,
                    'payment' => $verification->payment_proof_path ? $baseUrl . '/storage/' . $verification->payment_proof_path : null,
                    'neo_ig' => $verification->neo_path ? $baseUrl . '/storage/' . $verification->neo_path : null,
                    'marketing_ig' => $verification->marketing_path ? $baseUrl . '/storage/' . $verification->marketing_path : null,
                ],
                'submitted_at' => $verification->created_at,
                'verified_at' => $verification->verified_at
            ]
        ]);
    }

    /**
     * Admin: Approve verification
     * (This would require additional middleware for admin access)
     */
    public function approveVerification(Request $request, $id)
    {
        $verification = UserVerifikasi::findOrFail($id);
        $verification->verification_status = 'disetujui';
        $verification->verified_at = now();
        $verification->save();

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi berhasil disetujui',
            'data' => $verification
        ]);
    }

    /**
     * Admin: Reject verification
     * (This would require additional middleware for admin access)
     */
    public function rejectVerification(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $verification = UserVerifikasi::findOrFail($id);
        $verification->verification_status = 'ditolak';
        $verification->rejection_reason = $request->rejection_reason;
        $verification->save();

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi ditolak',
            'data' => $verification
        ]);
    }
}
