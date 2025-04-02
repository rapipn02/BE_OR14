<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserVerifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Get list of users with pagination and filters
     */
    public function getUsers(Request $request)
    {
        try {
            Log::info('Admin getUsers request', [
                'filters' => $request->all(),
                'user_id' => auth()->id()
            ]);

            $query = User::where('role', '!=', 'admin') // Add this line to exclude admins
                ->with(['profile', 'verification', 'exams' => function ($q) {
                    $q->with('division')->orderBy('created_at', 'desc');
                }]);

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                Log::info('Applying search filter', ['search' => $search]);

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('profile', function ($pq) use ($search) {
                            $pq->where('nama_lengkap', 'like', "%{$search}%")
                                ->orWhere('nim', 'like', "%{$search}%")
                                ->orWhere('whatsapp', 'like', "%{$search}%");
                        });
                });
            }

            // Apply status filter
            if ($request->has('status') && $request->status !== 'all') {
                Log::info('Applying status filter', ['status' => $request->status]);

                if ($request->status === 'not_submitted') {
                    $query->whereDoesntHave('verification');
                } else {
                    $query->whereHas('verification', function ($q) use ($request) {
                        $q->where('verification_status', $request->status);
                    });
                }
            }

            // Paginate results
            $perPage = $request->per_page ?? 10;
            $users = $query->paginate($perPage);

            Log::info('Users fetched successfully', [
                'count' => $users->count(),
                'total' => $users->total()
            ]);

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting users: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process verification (approve/reject)
     */
    public function processVerification(Request $request, $id)
    {
        try {
            Log::info('Processing verification', [
                'verification_id' => $id,
                'data' => $request->all()
            ]);

            $request->validate([
                'status' => 'required|in:approved,rejected',
                'notes' => 'nullable|string|max:500',
            ]);

            $verification = UserVerifikasi::findOrFail($id);

            // Map the status values
            $statusMap = [
                'approved' => 'disetujui',
                'rejected' => 'ditolak'
            ];

            $verification->verification_status = $statusMap[$request->status];

            if ($request->status === 'approved') {
                $verification->verified_at = now();
                $verification->rejection_reason = null;
            } else {
                $verification->rejection_reason = $request->notes;
            }

            $verification->save();

            Log::info('Verification processed successfully', [
                'verification_id' => $id,
                'status' => $verification->verification_status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Verification processed successfully',
                'data' => $verification
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing verification: ' . $e->getMessage(), [
                'verification_id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process verification: ' . $e->getMessage()
            ], 500);
        }
    }
}