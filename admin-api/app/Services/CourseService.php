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
        $application = CourseApplication::with(['course', 'student'])->find($id);

        if (!$application) {
            return response()->json([
                'status' => false,
                'message' => 'Application not found.',
                'code' => 404,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Application retrieved successfully.',
            'code' => 200,
            'data' => [
                'application' => $application,
            ],
        ]);
    }
    
    /**
     * Handle course creation.
     * 
     */
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'title'               => 'required|string',
            'description'         => 'nullable|string',
            'userId'              => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'teacher');
                }),
            ],
            'courseTags'          => 'nullable|array',
            'reapplyCooldownDays' => 'nullable|integer',
        ], [
            'userId.exists' => 'The selected user must be a valid user with teacher type.',
        ]);

        $validated = $validator->validate();

        $course = Course::create([
            'title'                 => $validated['title'],
            'description'           => $validated['description'] ?? null,
            'user_id'               => $validated['userId'],
            'course_tags'           => $validated['courseTags'] ?? null,
            'reapply_cooldown_days' => $validated['reapplyCooldownDays'] ?? 0,
        ]);

        return response()->json($course);
    }

    /**
     * Handle course update.
     * 
     */
    public function update($request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'               => 'sometimes|required|string',
            'description'         => 'nullable|string',
            'userId'              => [
                'sometimes',
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'teacher');
                }),
            ],
            'courseTags'          => 'nullable|array',
            'reapplyCooldownDays' => 'nullable|integer',
        ], [
            'userId.exists' => 'The selected user must be a valid user with teacher type.',
        ]);

        $validated = $validator->validate();

        $course->update([
            'title'                 => $validated['title'] ?? $course->title,
            'description'           => array_key_exists('description', $validated) 
                ? $validated['description'] 
                : $course->description,
            'user_id'               => $validated['userId'] ?? $course->user_id,
            'course_tags'           => array_key_exists('courseTags', $validated) 
                ? $validated['courseTags'] 
                : $course->course_tags,
            'reapply_cooldown_days' => array_key_exists('reapplyCooldownDays', $validated) 
                ? $validated['reapplyCooldownDays'] 
                : $course->reapply_cooldown_days,
        ]);

        return response()->json($course);
    }

    /**
     * Handle course deletion.
     * 
     */
    public function destroy($id)
    {
        $course = Course::find($id);

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