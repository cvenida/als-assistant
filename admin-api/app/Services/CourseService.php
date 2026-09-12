<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseApplication;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseService
{
    /**
     * Get all course by user logged in.
     * 
     */
    public function index()
    {
        $user = auth()->user();
        $courses = Course::where('user_id', $user->id)->get();

        return response()->json($courses);
    }

    /**
     * Get a specific course.
     * 
     */
    public function show($id)
    {
        $course = Course::with(['activities.questions.options'])->find($id);

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found.',
                'code' => 404,
            ], 404);
        }

        return response()->json($course);
    }
    
    /**
     * Handle course creation.
     * 
     */
    public function store($request)
    {
        $user = auth()->user();

        if ($user->type !== 'teacher') {
            return response()->json([
                'message' => 'Unauthorized. Only teachers can create courses.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'                 => 'required|string',
            'description'           => 'nullable|string',
            'tags'                  => 'nullable|array',
            'cooldownDays' => 'nullable|integer',
        ], [
            'userId.exists' => 'The selected user must be a valid user with teacher type.',
        ]);

        $validated = $validator->validate();

        $course = Course::create([
            'title'                 => $validated['title'],
            'description'           => $validated['description'] ?? null,
            'user_id'               => $user->id,
            'course_tags'           => $validated['tags'] ?? null,
            'reapply_cooldown_days' => $validated['cooldownDays'] ?? 0,
        ]);

        return response()->json($course);
    }

    /**
     * Handle course update.
     */
    public function update($request, $id)
    {
        $user = auth()->user();

        if ($user->type !== 'teacher') {
            return response()->json([
                'message' => 'Unauthorized. Only teachers can update courses.'
            ], 403);
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found.',
            ], 404);
        }

        if ($course->user_id !== $user->id) {
            return response()->json([
                'message' => 'Forbidden. You do not have permission to update this course.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title'        => 'sometimes|required|string',
            'description'  => 'nullable|string',
            'tags'         => 'nullable|array',
            'status'       => 'sometimes|required|string|in:draft,active,inactive',
            'cooldownDays' => 'nullable|integer',
        ]);

        $validated = $validator->validate();

        $course->update([
            'title'=> $validated['title'] ?? $course->title,
            'description'=> array_key_exists('description', $validated) 
                ? $validated['description'] 
                : $course->description,
            'course_tags'=> array_key_exists('tags', $validated) 
                ? $validated['tags'] 
                : $course->course_tags,
            'reapply_cooldown_days' => array_key_exists('cooldownDays', $validated) 
                ? $validated['cooldownDays'] 
                : $course->reapply_cooldown_days,
            'status'=> $validated['status'] ?? $course->status,
        ]);

        return response()->json($course);
    }

    /**
     * Handle course deletion.
     * 
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if ($user->type !== 'teacher') {
            return response()->json([
                'message' => 'Unauthorized. Only teachers can update courses.'
            ], 403);
        }
        
        $course = Course::find($id);

        if ($course->user_id !== $user->id) {
            return response()->json([
                'message' => 'Forbidden. You do not have permission to update this course.'
            ], 403);
        }

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found.',
            ], 404);
        }

        $course->delete();

        return response()->json([
            'status' => true,
            'message' => 'Course deleted successfully.',
        ]);
    }
}