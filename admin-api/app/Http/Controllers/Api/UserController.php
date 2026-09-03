<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    /***
     * Construct
     * 
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * User registration function
     * 
     */
    public function register(Request $request)
    {
        $result = $this->userService->register($request);

        return response()->json($result);
    }

    /**
     * User login function
     * 
     */
    public function login(Request $request)
    {
        $result = $this->userService->login($request);

        return response()->json($result);
    }
}