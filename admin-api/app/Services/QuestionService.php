<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuestionService
{

    /**
     * Handle question creation along with options.
     * 
     */
    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'activityId'           => 'required|exists:activities,id',
            'questionText'         => 'required|string',
            'questionType'         => 'required|string',
            'points'               => 'required|integer',
            'options'              => 'required|array|min:1',
            'options.*.optionText' => 'required|string',
            'options.*.isCorrect'  => 'required|boolean',
        ]);

        $validated = $validator->validate();

        $question = DB::transaction(function () use ($validated) {
            $createdQuestion = Question::create([
                'activity_id'   => $validated['activityId'],
                'question_text' => $validated['questionText'],
                'question_type' => $validated['questionType'],
                'points'        => $validated['points'],
            ]);

            foreach ($validated['options'] as $option) {
                QuestionOption::create([
                    'question_id' => $createdQuestion->id,
                    'option_text' => $option['optionText'],
                    'is_correct'  => $option['isCorrect'],
                ]);
            }

            return $createdQuestion->load('options');
        });

        return response()->json($question);
    }

    /**
     * Handle question and options update.
     * 
     */
    public function update($request, $id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => false,
                'message' => 'Question not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'activityId'           => 'sometimes|required|exists:activities,id',
            'questionText'         => 'sometimes|required|string',
            'questionType'         => 'sometimes|required|string',
            'points'               => 'sometimes|required|integer',
            'options'              => 'nullable|array|min:1',
            'options.*.optionText' => 'required_with:options|string',
            'options.*.isCorrect'  => 'required_with:options|boolean',
        ]);

        $validated = $validator->validate();

        $updatedQuestion = DB::transaction(function () use ($question, $validated) {
            $question->update([
                'activity_id'   => $validated['activityId'] ?? $question->activity_id,
                'question_text' => $validated['questionText'] ?? $question->question_text,
                'question_type' => $validated['questionType'] ?? $question->question_type,
                'points'        => $validated['points'] ?? $question->points,
            ]);

            if (isset($validated['options'])) {
                QuestionOption::where('question_id', $question->id)->delete();
                foreach ($validated['options'] as $option) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $option['optionText'],
                        'is_correct'  => $option['isCorrect'],
                    ]);
                }
            }

            return $question->load('options');
        });

        return response()->json($updatedQuestion);
    }

    /**
     * Handle question deletion.
     * 
     */
    public function destroy($id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json([
                'status' => false,
                'message' => 'Question not found.',
            ], 404);
        }

        $question->delete();

        return response()->json([
            'status' => true,
            'message' => 'Question deleted successfully.',
        ]);
    }
}