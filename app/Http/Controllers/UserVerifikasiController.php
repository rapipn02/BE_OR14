<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserVerifikasi;
use App\Models\UserProfile; // Model UserProfile
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserVerifikasiController extends Controller
{
    public function uploadFiles(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized.'], 401);
    }

    // Cek apakah user memiliki profil yang sudah lengkap
    $userProfile = UserProfile::where('user_id', $user->id)->first();

    if (!$userProfile) {
        return response()->json([
            'message' => 'Silakan lengkapi profil terlebih dahulu sebelum mengunggah file.'
        ], 403);
    }

    // Pastikan semua field penting sudah diisi
    $requiredFields = ['nama_lengkap', 'panggilan', 'nim', 'whatsapp', 'program_studi', 'divisi', 'sub_divisi'];
    foreach ($requiredFields as $field) {
        if (empty($userProfile->$field)) {
            return response()->json(['message' => 'Profil belum lengkap, harap lengkapi terlebih dahulu.'], 403);
        }
    }

    // Validasi file
    $request->validate([
        'krs' => 'required|file|mimes:pdf|max:2048',
        'payment_proof' => 'required|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Simpan file di storage
    $krsPath = $request->file('krs')->store('uploads/krs', 'public');
    $paymentProofPath = $request->file('payment_proof')->store('uploads/payment_proof', 'public');

    // Simpan data verifikasi ke database
    $userVerifikasi = UserVerifikasi::updateOrCreate(
        ['user_id' => $user->id], 
        [
            'krs_path' => $krsPath,
            'payment_proof_path' => $paymentProofPath
        ]
    );

    return response()->json([
        'message' => 'File berhasil diunggah!',
        'data' => [
            'krs_url' => Storage::url($userVerifikasi->krs_path),
            'payment_proof_url' => Storage::url($userVerifikasi->payment_proof_path),
        ]
    ], 201);
}

}
