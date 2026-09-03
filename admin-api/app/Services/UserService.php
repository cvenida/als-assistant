<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserService
{
    /**
     * Handle user registration without generating an immediate token.
     * 
     */
    public function register($request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName'  => 'required|string',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|string|min:6',
            'type'       => 'required|in:teacher,student',
        ]);

        $validated = $validator->validate();

        $user = User::create([
            'first_name' => $validated['firstName'],
            'last_name'  => $validated['lastName'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'type'       => $validated['type'],
        ]);

        return [
            'status' => true,
            'message' => 'User registered successfully.',
            'code' => 201,
            'data' => [
                'user' => [
                    'id'         => $user->id,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'email'      => $user->email,
                    'type'       => $user->type,
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
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'type' => $user->type,
                    'email' => $user->email,
                ],
            ],
        ];
    }
}