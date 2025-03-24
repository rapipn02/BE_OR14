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
     * Get user profile
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

        return response()->json([
            'status' => 'success',
            'data' => $profile,
            'photo_url' => $profile->photo ? url(Storage::url($profile->photo)) : null
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

        return response()->json([
            'status' => 'success',
            'message' => 'Profile created successfully',
            'data' => $profile,
            'photo_url' => $photoPath ? url(Storage::url($photoPath)) : null
        ], 201);
    }

    /**
     * Update profile
     */
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
            'twibbon' => 'nullable|string|url',
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
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }

            // Store new photo
            $photoPath = $request->file('photo')->store('profile_photos', 'public');
            $profile->photo = $photoPath;
        }

        // Update profile
        $profile->nama_lengkap = $request->nama_lengkap;
        $profile->panggilan = $request->panggilan;
        $profile->nim = $request->nim;
        $profile->whatsapp = $request->whatsapp;
        $profile->program_studi = $request->program_studi;
        $profile->departemen = $request->departemen;
        $profile->divisi = $request->divisi;
        $profile->sub_divisi = $request->sub_divisi;
        $profile->twibbon = $request->twibbon;
        $profile->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $profile,
            'photo_url' => $profile->photo ? url(Storage::url($profile->photo)) : null
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

        // Check required fields
        $requiredFields = [
            'nama_lengkap',
            'panggilan',
            'nim',
            'whatsapp',
            'program_studi',
            'divisi',
            'sub_divisi'
        ];

        $isComplete = true;
        foreach ($requiredFields as $field) {
            if (empty($profile->$field)) {
                $isComplete = false;
                break;
            }
        }

        return response()->json([
            'status' => 'success',
            'is_complete' => $isComplete,
            'profile' => $profile,
            'photo_url' => $profile->photo ? url(Storage::url($profile->photo)) : null
        ]);
    }
}
