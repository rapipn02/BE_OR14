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
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        // Check if user already has a verification submission
        $verification = UserVerifikasi::where('user_id', $user->id)->first();

        // If there's a previous verification and it was rejected, delete the old document
        if ($verification && $verification->status === 'rejected') {
            if ($verification->document_path) {
                Storage::disk('public')->delete($verification->document_path);
            }
            $verification->delete();
            $verification = null;
        }

        // If the user already has a pending or verified verification, return an error
        if ($verification) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a ' . $verification->status . ' verification submission'
            ], 400);
        }

        // Store the document
        $documentPath = $request->file('document')->store('verification_documents', 'public');

        // Create verification record
        $verification = UserVerifikasi::create([
            'user_id' => $user->id,
            'document_path' => $documentPath,
            'status' => 'pending',
            'notes' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Verification document uploaded successfully',
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
                'message' => 'Verification not submitted yet',
                'status' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => $verification->status,
            'notes' => $verification->notes,
            'document_url' => $verification->document_path ? Storage::url($verification->document_path) : null,
            'submitted_at' => $verification->created_at
        ]);
    }
}
