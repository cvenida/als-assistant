<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\QuestionService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    protected QuestionService $questionService;

    /***
     * Construct
     * 
     */
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Create question function
     * 
     */
    public function store(Request $request)
    {
        return $this->questionService->store($request);
    }

    /**
     * Update question function
     * 
     */
    public function update(Request $request, $id)
    {
        return $this->questionService->update($request, $id);
    }

    /**
     * Delete question function
     * 
     */
    public function destroy($id)
    {
        return $this->questionService->destroy($id);
    }
}