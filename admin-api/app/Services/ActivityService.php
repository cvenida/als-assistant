<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\CourseApplication;
use Illuminate\Support\Facades\Validator;

class ActivityService
{
    /**
     * Get all activities.
     * 
     */
    public function index()
    {
        $activities = Activity::with('course')->get();

        return response()->json($activities);
    }

    /**
     * Get a specific activity by ID.
     * 
     */
    public function show($id)
    {
        $activity = Activity::with(['course', 'questions.options'])->find($id);

        if (!$activity) {
            return response()->json([
                'status' => false,
                'message' => 'Activity not found.',
            ], 404);
        }

        return response()->json($activity);
    }
    
    /**
     * Handle activity creation.
     * 
     */
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'courseId' => 'required|exists:courses,id',
            'title'    => 'required|string',
            'type'     => 'required|in:quiz,assignment,exam,practice',
            'deadline' => 'nullable|date',
        ]);

        $validated = $validator->validate();

        $activity = Activity::create([
            'course_id' => $validated['courseId'],
            'title'     => $validated['title'],
            'type'      => $validated['type'],
            'deadline'  => $validated['deadline'] ?? null,
        ]);

        return response()->json($activity);
    }

    /**
     * Handle activity update.
     * 
     */
    public function update($request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'status' => false,
                'message' => 'Activity not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'courseId' => 'sometimes|required|exists:courses,id',
            'title'    => 'sometimes|required|string',
            'type'     => 'sometimes|required|in:quiz,assignment,exam,practice',
            'deadline' => 'nullable|date',
        ]);

        $validated = $validator->validate();

        $activity->update([
            'course_id' => $validated['courseId'] ?? $activity->course_id,
            'title'     => $validated['title'] ?? $activity->title,
            'type'      => $validated['type'] ?? $activity->type,
            'deadline'  => array_key_exists('deadline', $validated) ? $validated['deadline'] : $activity->deadline,
        ]);

        return response()->json($activity);
    }

    /**
     * Handle activity deletion.
     * 
     */
    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'status' => false,
                'message' => 'Activity not found.',
            ], 404);
        }

        $activity->delete();

        return response()->json([
            'status' => true,
            'message' => 'Activity deleted successfully.',
        ]);
    }
}