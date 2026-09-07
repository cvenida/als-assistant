<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CourseApplicationService;
use Illuminate\Http\Request;

class CourseApplicationController extends Controller
{
    protected CourseApplicationService $courseApplicationService;

    /***
     * Construct
     * 
     */
    public function __construct(CourseApplicationService $courseApplicationService)
    {
        $this->courseApplicationService = $courseApplicationService;
    }

    /**
     * List all applications function
     * 
     */
    public function index()
    {
        return $this->courseApplicationService->index();
    }

    /**
     * Show single application function
     * 
     */
    public function show($id)
    {
        return $this->courseApplicationService->show($id);
    }

    /**
     * Apply for course function
     * 
     */
    public function store(Request $request)
    {
        return $this->courseApplicationService->apply($request);
    }

    /**
     * Update application status function
     * 
     */
    public function updateStatus(Request $request, $id)
    {
        return $this->courseApplicationService->updateStatus($request, $id);
    }

    /**
     * Delete application function
     * 
     */
    public function destroy($id)
    {
        return $this->courseApplicationService->destroy($id);
    }
}