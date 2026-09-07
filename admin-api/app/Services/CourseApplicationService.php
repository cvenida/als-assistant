<?php

namespace App\Services;

use App\Models\CourseApplication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CourseApplicationService
{
    /**
     * Get all course applications.
     * 
     */
    public function index()
    {
        $applications = CourseApplication::with(['course', 'student'])->get();

        return response()->json($applications);
    }

    /**
     * Get a specific course application by ID.
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

        return response()->json($application);
    }

    /**
     * Handle student application submission.
     * 
     */
    public function apply($request)
    {
        $validator = Validator::make($request->all(), [
            'courseId' => 'required|exists:courses,id',
            'userId'   => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('type', 'student');
                }),
            ],
        ], [
            'userId.exists' => 'The selected user must be a valid user with student type.',
        ]);

        $validated = $validator->validate();

        $existingApplication = CourseApplication::where('course_id', $validated['courseId'])
            ->where('user_id', $validated['userId'])
            ->latest('created_at')
            ->first();

        if ($existingApplication) {
            if ($existingApplication->status === 'pending') {
                return response()->json([
                    'status' => false,
                    'message' => 'You already have a pending application for this course.',
                ], 400);
            }

            if ($existingApplication->reapply_eligible_at && Carbon::now()->lt($existingApplication->reapply_eligible_at)) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not yet eligible to reapply for this course.',
                ], 422);
            }
        }

        $application = CourseApplication::create([
            'course_id'           => $validated['courseId'],
            'user_id'             => $validated['userId'],
            'status'              => 'pending',
            'reapply_eligible_at' => null,
        ]);

        return response()->json($application);
    }

    /**
     * Handle updating application status.
     * 
     */
    public function updateStatus($request, $id)
    {
        $application = CourseApplication::with('course')->find($id);

        if (!$application) {
            return response()->json([
                'status' => false,
                'message' => 'Application not found.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected,pending',
        ]);

        $validated = $validator->validate();

        $reapplyEligibleAt = null;
        if ($validated['status'] === 'rejected' && $application->course->reapply_cooldown_days > 0) {
            $reapplyEligibleAt = Carbon::now()->addDays($application->course->reapply_cooldown_days);
        }

        $application->update([
            'status'              => $validated['status'],
            'reapply_eligible_at' => $reapplyEligibleAt,
        ]);

        return response()->json($application);
    }

    /**
     * Handle application deletion.
     * 
     */
    public function destroy($id)
    {
        $application = CourseApplication::find($id);

        if (!$application) {
            return response()->json([
                'status' => false,
                'message' => 'Application not found.',
            ], 404);
        }

        $application->delete();

        return response()->json([
            'status' => true,
            'message' => 'Application deleted successfully.',
        ]);
    }
}