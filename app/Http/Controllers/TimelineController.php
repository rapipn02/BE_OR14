<?php

namespace App\Http\Controllers;

use App\Models\Timeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TimelineController extends Controller
{
    /**
     * Get all timeline items
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimelines()
    {
        try {
            $timelines = Timeline::orderBy('order')->get();

            return response()->json([
                'success' => true,
                'data' => $timelines
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting timelines: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve timelines: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new timeline item
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTimeline(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'start_date' => 'nullable|string|max:255',
                'order' => 'nullable|integer',
                'is_enabled' => 'nullable|boolean',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // If order is not provided, set it to the end
            if (!$request->has('order')) {
                $maxOrder = Timeline::max('order') ?? 0;
                $request->merge(['order' => $maxOrder + 1]);
            }

            $timeline = Timeline::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Timeline created successfully',
                'data' => $timeline
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating timeline: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create timeline: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a timeline item
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTimeline(Request $request, $id)
    {
        try {
            $timeline = Timeline::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'start_date' => 'nullable|string|max:255',
                'order' => 'nullable|integer',
                'is_enabled' => 'nullable|boolean',
                'is_active' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $timeline->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Timeline updated successfully',
                'data' => $timeline
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating timeline: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update timeline: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a timeline item
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTimeline($id)
    {
        try {
            $timeline = Timeline::findOrFail($id);
            $timeline->delete();

            // Reorder the remaining timelines
            $timelines = Timeline::orderBy('order')->get();
            foreach ($timelines as $index => $item) {
                $item->update(['order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Timeline deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting timeline: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete timeline: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder timeline items
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorderTimelines(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'timelines' => 'required|array',
                'timelines.*.id' => 'required|exists:timelines,id',
                'timelines.*.order' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            foreach ($request->timelines as $item) {
                Timeline::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Timelines reordered successfully',
                'data' => Timeline::orderBy('order')->get()
            ]);
        } catch (\Exception $e) {
            Log::error('Error reordering timelines: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder timelines: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get timeline items for user dashboard (public view)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicTimelines()
    {
        try {
            $timelines = Timeline::where('is_enabled', true)
                ->orderBy('order')
                ->get()
                ->map(function ($timeline) {
                    return [
                        'id' => $timeline->id,
                        'title' => $timeline->title,
                        'date' => $timeline->start_date ?: $timeline->formatted_date,
                        'isActive' => $timeline->is_active // Gunakan property is_active langsung
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $timelines
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting public timelines: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve timelines: ' . $e->getMessage()
            ], 500);
        }
    }
}
