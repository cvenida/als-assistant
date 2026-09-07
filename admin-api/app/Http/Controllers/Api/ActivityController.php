<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected ActivityService $activityService;

    /***
     * Construct
     * 
     */
    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * Create activity function
     * 
     */
    public function store(Request $request)
    {
        return $this->activityService->store($request);
    }

    /**
     * Update activity function
     * 
     */
    public function update(Request $request, $id)
    {
        return $this->activityService->update($request, $id);
    }

    /**
     * Delete activity function
     * 
     */
    public function destroy($id)
    {
        return $this->activityService->destroy($id);
    }
}