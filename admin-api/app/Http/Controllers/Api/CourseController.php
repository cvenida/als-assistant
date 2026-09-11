<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected CourseService $courseService;

    /***
     * Construct
     * 
     */
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * List all courses function
     * 
     */
    public function index()
    {
        return $this->courseService->index();
    }

    /**
     * Show single course function
     * 
     */
    public function show($id)
    {
        return $this->courseService->show($id);
    }

    /**
     * Create course function
     * 
     */
    public function store(Request $request)
    {
        return $this->courseService->store($request);
    }

    /**
     * Update course function
     * 
     */
    public function update(Request $request, $id)
    {
        return $this->courseService->update($request, $id);
    }

    /**
     * Delete course function
     * 
     */
    public function destroy($id)
    {
        return $this->courseService->destroy($id);
    }
}