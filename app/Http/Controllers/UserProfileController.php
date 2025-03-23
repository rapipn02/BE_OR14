<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    // **1. Store (Membuat Profil)**
    public function store(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'panggilan' => 'required|string|max:50',
            'nim' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:15',
            'program_studi' => 'nullable|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'sub_divisi' => 'nullable|string|max:100',
            'twibbon' => 'nullable|url',
            'photo' => 'nullable|image|max:2048', // Maks 2MB
        ]);

        // Cek apakah user ada
        $user = User::findOrFail($id);

        // Cek apakah profil sudah ada
        if (UserProfile::where('user_id', $id)->exists()) {
            return response()->json(['message' => 'Profil sudah ada'], 400);
        }

        // Simpan foto jika diunggah
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
        }

        // Simpan profil
        $profile = UserProfile::create([
            'user_id' => $id,
            'nama_lengkap' => $request->nama_lengkap,
            'panggilan' => $request->panggilan,
            'nim' => $request->nim,
            'whatsapp' => $request->whatsapp,
            'program_studi' => $request->program_studi,
            'divisi' => $request->divisi,
            'sub_divisi' => $request->sub_divisi,
            'twibbon' => $request->twibbon,
            'photo' => $photoPath,
        ]);

        return response()->json(['message' => 'Profil berhasil dibuat', 'profile' => $profile], 201);
    }

    // **2. Show (Menampilkan Profil)**
    public function show($id)
    {
        $profile = UserProfile::where('user_id', $id)->first();

        if (!$profile) {
            return response()->json(['message' => 'Profil tidak ditemukan'], 404);
        }

        return response()->json($profile);
    }

    // **3. Update Profil**
    public function update(Request $request, $id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();

        $request->validate([
            'nama_lengkap' => 'string|max:255',
            'panggilan' => 'string|max:50',
            'nim' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:15',
            'program_studi' => 'nullable|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'sub_divisi' => 'nullable|string|max:100',
            'twibbon' => 'nullable|url',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Simpan foto baru jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            // Simpan foto baru
            $profile->photo = $request->file('photo')->store('profile_photos', 'public');
        }

        // Update data profil
        $profile->update($request->except(['photo']));

        return response()->json(['message' => 'Profil berhasil diperbarui', 'profile' => $profile]);
    }

    // **4. Delete (Hapus Profil)**
    public function destroy($id)
    {
        $profile = UserProfile::where('user_id', $id)->firstOrFail();

        // Hapus foto jika ada
        if ($profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

        $profile->delete();

        return response()->json(['message' => 'Profil berhasil dihapus']);
    }
    

    // UserProfileController.php
    public function isProfileComplete($id)
{
    $profile = UserProfile::where('user_id', $id)->first();

    if (!$profile) {
        return response()->json(['message' => 'Profil belum dibuat'], 404);
    }

    // Cek apakah semua field wajib sudah diisi
    $requiredFields = ['nama_lengkap', 'panggilan', 'nim', 'whatsapp', 'program_studi', 'divisi', 'sub_divisi'];
    foreach ($requiredFields as $field) {
        if (empty($profile->$field)) {
            return response()->json(['message' => 'Profil belum lengkap'], 400);
        }
    }

    return response()->json(['message' => 'Profil sudah lengkap'], 200);
}

}

