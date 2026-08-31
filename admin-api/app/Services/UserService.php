<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class UserService
{
    /**
     * Handle user registration without generating an immediate token.
     * 
     */
    public function register($request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return [
            'status' => true,
            'message' => 'User registered successfully. Please log in.',
            'code' => 201,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ],
            ],
        ];
    }

    /**
     * Handle user authentication and JWT token creation.
     * 
     */
    public function login($request)
    {   
        $user = User::where('email', $request['email'])->first();

        if (!$user || !Hash::check($request['password'], $user->password)) {
            return [
                'status' => false,
                'message' => 'Invalid email or password request.',
                'code' => 401,
            ];
        }

        $token = JWTAuth::fromUser($user);

        return [
            'status' => true,
            'message' => 'Login successful.',
            'code' => 200,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60, // 1 hour
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
        ];
    }
}