<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\CourseApplication;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;
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
            'course_id'                   => 'required|exists:courses,id',
            'title'                       => 'required|string',
            'type'                        => 'required|in:quiz,assignment,exam,practice',
            'deadline'                    => 'nullable|date',
            'questions'                   => 'nullable|array',
            'questions.*.question_text'   => 'required_with:questions|string',
            'questions.*.question_type'   => 'required_with:questions|string',
            'questions.*.points'          => 'required_with:questions|integer',
            'questions.*.options'         => 'nullable|array',
            'questions.*.options.*.option_text' => 'required_with:questions.*.options|string',
            'questions.*.options.*.is_correct'  => 'required_with:questions.*.options|boolean',
        ]);

        $validated = $validator->validate();

        $activity = DB::transaction(function () use ($validated) {
            $createdActivity = Activity::create([
                'course_id' => $validated['course_id'],
                'title'     => $validated['title'],
                'type'      => $validated['type'],
                'deadline'  => $validated['deadline'] ?? null,
            ]);

            if (!empty($validated['questions'])) {
                foreach ($validated['questions'] as $qData) {
                    $question = Question::create([
                        'activity_id'   => $createdActivity->id,
                        'question_text' => $qData['question_text'],
                        'question_type' => $qData['question_type'],
                        'points'        => $qData['points'],
                    ]);

                    if (!empty($qData['options'])) {
                        foreach ($qData['options'] as $opt) {
                            QuestionOption::create([
                                'question_id' => $question->id,
                                'option_text' => $opt['option_text'],
                                'is_correct'  => $opt['is_correct'],
                            ]);
                        }
                    }
                }
            }

            return $createdActivity->load('questions.options');
        });

        return response()->json($activity);
    }

    /**
     * Update activity along with its questions and options.
     */
    public function update($request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'status'  => false,
                'message' => 'Activity not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'course_id'                   => 'sometimes|required|exists:courses,id',
            'title'                       => 'sometimes|required|string',
            'type'                        => 'sometimes|required|in:quiz,assignment,exam,practice',
            'deadline'                    => 'nullable|date',
            'questions'                   => 'nullable|array',
            'questions.*.question_text'   => 'required_with:questions|string',
            'questions.*.question_type'   => 'required_with:questions|string',
            'questions.*.points'          => 'required_with:questions|integer',
            'questions.*.options'         => 'nullable|array',
            'questions.*.options.*.option_text' => 'required_with:questions.*.options|string',
            'questions.*.options.*.is_correct'  => 'required_with:questions.*.options|boolean',
        ]);

        $validated = $validator->validate();

        $updatedActivity = DB::transaction(function () use ($activity, $validated) {
            $activity->update([
                'course_id' => $validated['course_id'] ?? $activity->course_id,
                'title'     => $validated['title'] ?? $activity->title,
                'type'      => $validated['type'] ?? $activity->type,
                'deadline'  => array_key_exists('deadline', $validated) ? $validated['deadline'] : $activity->deadline,
            ]);

            if (isset($validated['questions'])) {
                Question::where('activity_id', $activity->id)->delete();

                foreach ($validated['questions'] as $qData) {
                    $question = Question::create([
                        'activity_id'   => $activity->id,
                        'question_text' => $qData['question_text'],
                        'question_type' => $qData['question_type'],
                        'points'        => $qData['points'],
                    ]);

                    if (!empty($qData['options'])) {
                        foreach ($qData['options'] as $opt) {
                            QuestionOption::create([
                                'question_id' => $question->id,
                                'option_text' => $opt['option_text'],
                                'is_correct'  => $opt['is_correct'],
                            ]);
                        }
                    }
                }
            }

            return $activity->load('questions.options');
        });

        return response()->json($updatedActivity);
    }

    /**
     * Handle activity deletion.
     */
    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'status'  => false,
                'message' => 'Activity not found.',
            ], 404);
        }

        $activity->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Activity deleted successfully.',
        ]);
    }
}