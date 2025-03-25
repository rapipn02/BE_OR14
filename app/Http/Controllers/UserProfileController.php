<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Get user profile - Make sure this method is used instead of 'show'
     */
    public function getProfile()
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profile not found'
            ], 404);
        }

        // Let the model handle the photo_url via appended attribute
        return response()->json([
            'status' => 'success',
            'data' => $profile
        ]);
    }

    /**
     * Create a new profile
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if profile already exists
        if (UserProfile::where('user_id', $user->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profile already exists'
            ], 400);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'panggilan' => 'required|string|max:50',
            'nim' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:15',
            'program_studi' => 'required|string|max:100',
            'departemen' => 'nullable|string|max:100',
            'divisi' => 'required|string|max:100',
            'sub_divisi' => 'required|string|max:100',
            'twibbon' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
        }

        // Create new profile
        $profile = new UserProfile();
        $profile->user_id = $user->id;
        $profile->nama_lengkap = $request->nama_lengkap;
        $profile->panggilan = $request->panggilan;
        $profile->nim = $request->nim;
        $profile->whatsapp = $request->whatsapp;
        $profile->program_studi = $request->program_studi;
        $profile->departemen = $request->departemen;
        $profile->divisi = $request->divisi;
        $profile->sub_divisi = $request->sub_divisi;
        $profile->twibbon = $request->twibbon;
        $profile->photo = $photoPath;
        $profile->save();

        // Refresh to include appended attributes
        $profile->refresh();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile created successfully',
            'data' => $profile
        ], 201);
    }


    private function handlePhotoUpload(Request $request, UserProfile $profile = null)
    {
        // Periksa apakah ada file foto yang diupload
        if (!$request->hasFile('photo')) {
            return null;
        }

        $file = $request->file('photo');

        // Log informasi file
        \Log::info('Handling photo upload:', [
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType()
        ]);

        // Hapus foto lama jika ada
        if ($profile && $profile->photo) {
            $oldPath = $profile->photo;
            \Log::info('Deleting old photo: ' . $oldPath);

            try {
                Storage::disk('public')->delete($oldPath);
            } catch (\Exception $e) {
                \Log::warning('Failed to delete old photo: ' . $e->getMessage());
            }
        }

        // Simpan foto baru dengan nama random yang unik
        try {
            // Gunakan sistem penamaan yang sangat unik
            $uniqueName = uniqid('profile_', true) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_photos', $uniqueName, 'public');

            // Pastikan file berhasil disimpan
            if (!$path) {
                \Log::error('Failed to store photo: path is empty');
                return null;
            }

            \Log::info('Photo successfully saved at: ' . $path);
            return $path;
        } catch (\Exception $e) {
            \Log::error('Exception when saving photo: ' . $e->getMessage());
            return null;
        }
    }

    public function saveProfile(Request $request)
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();
        $isNew = !$profile;

        // Log semua data request untuk debugging
        \Log::info('Save profile request data:', $request->all());

        try {
            // Jika ini update (profile sudah ada)
            if (!$isNew) {
                // Validasi field yang ada saja, tanpa required
                $rules = [];

                if ($request->has('nama_lengkap')) {
                    $rules['nama_lengkap'] = 'string|max:255';
                }

                if ($request->has('panggilan')) {
                    $rules['panggilan'] = 'string|max:50';
                }

                if ($request->has('nim')) {
                    $rules['nim'] = 'string|max:20';
                }

                if ($request->has('whatsapp')) {
                    $rules['whatsapp'] = 'string|max:15';
                }

                if ($request->has('program_studi')) {
                    $rules['program_studi'] = 'string|max:100';
                }

                if ($request->has('departemen')) {
                    $rules['departemen'] = 'nullable|string|max:100';
                }

                if ($request->has('divisi')) {
                    $rules['divisi'] = 'string|max:100';
                }

                if ($request->has('sub_divisi')) {
                    $rules['sub_divisi'] = 'string|max:100';
                }

                if ($request->has('twibbon')) {
                    $rules['twibbon'] = 'nullable|string';
                }

                if ($request->hasFile('photo')) {
                    $rules['photo'] = 'image|max:2048';
                }

                $validator = Validator::make($request->all(), $rules);
            } else {
                // Validasi untuk profile baru (create)
                $validator = Validator::make($request->all(), [
                    'nama_lengkap' => 'required|string|max:255',
                    'panggilan' => 'required|string|max:50',
                    'nim' => 'required|string|max:20',
                    'whatsapp' => 'required|string|max:15',
                    'program_studi' => 'required|string|max:100',
                    'divisi' => 'required|string|max:100',
                    'sub_divisi' => 'required|string|max:100',
                    'departemen' => 'nullable|string|max:100',
                    'twibbon' => 'nullable|string',
                    'photo' => 'nullable|image|max:2048',
                ]);
            }

            if ($validator->fails()) {
                \Log::error('Validation error:', $validator->errors()->toArray());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buat profile baru jika belum ada
            if ($isNew) {
                $profile = new UserProfile();
                $profile->user_id = $user->id;
            }
            if ($request->hasFile('photo')) {
                $photoPath = $this->handlePhotoUpload($request, $isNew ? null : $profile);

                if ($photoPath) {
                    $profile->photo = $photoPath;
                    \Log::info('Setting photo path to: ' . $photoPath);
                } else {
                    \Log::warning('Photo upload failed, no path returned');
                }
            }

            // Update fields lain sesuai request
            $changes = [];
            foreach ($request->except(['photo', '_token', '_method']) as $key => $value) {
                if (in_array($key, $profile->getFillable()) && $key !== 'photo' && $key !== 'user_id') {
                    $oldValue = $profile->$key;
                    $profile->$key = $value;
                    $changes[$key] = ['from' => $oldValue, 'to' => $value];
                }
            }

            // Simpan perubahan ke database
            $profile->save();

            // Verifikasi bahwa perubahan tersimpan
            $profile->refresh();

            \Log::info('Profile data after save:', [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'photo' => $profile->photo,
                'photo_url' => $profile->photo_url
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $isNew ? 'Profile created successfully' : 'Profile updated successfully',
                'data' => $profile,
                'changes' => $changes
            ], $isNew ? 201 : 200);
        } catch (\Exception $e) {
            \Log::error('Exception in saveProfile:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profile not found'
            ], 404);
        }

        // Validasi semua field yang dikirim
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'sometimes|string|max:255',
            'panggilan' => 'sometimes|string|max:50',
            'nim' => 'sometimes|string|max:20',
            'whatsapp' => 'sometimes|string|max:15',
            'program_studi' => 'sometimes|string|max:100',
            'departemen' => 'sometimes|nullable|string|max:100',
            'divisi' => 'sometimes|string|max:100',
            'sub_divisi' => 'sometimes|string|max:100',
            'twibbon' => 'sometimes|nullable|string',
            'photo' => 'sometimes|nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }

            // Store new photo
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $profile->photo = $photoPath;
        }

        // Update data yang dikirim ke database
        foreach ($request->all() as $key => $value) {
            // Hanya update field yang ada dalam fillable model
            if (in_array($key, $profile->getFillable()) && $key !== 'photo' && $key !== 'user_id') {
                $profile->$key = $value;
            }
        }

        // Simpan perubahan
        $profile->save();

        // Refresh untuk mendapatkan data terbaru termasuk atribut tambahan
        $profile->refresh();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $profile
        ]);
    }

    /**
     * Check if profile is complete
     */
    public function checkProfileStatus()
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json([
                'status' => 'success',
                'is_complete' => false,
                'profile' => null
            ]);
        }

        $isComplete = $profile->isComplete();

        return response()->json([
            'status' => 'success',
            'is_complete' => $isComplete,
            'data' => $profile
        ]);
    }
}
