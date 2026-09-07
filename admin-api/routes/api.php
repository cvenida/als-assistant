<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\CourseApplicationController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\QuestionController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome Moji!';
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);

Route::resource('courses', CourseController::class)->only(['store', 'update', 'destroy']);
Route::resource('activities', ActivityController::class)->only(['store', 'update', 'destroy']);
Route::resource('questions', QuestionController::class)->only(['store', 'update', 'destroy']);

Route::resource('course-applications', CourseApplicationController::class)->only(['store', 'destroy']);
Route::put('course-applications/{id}/status', [CourseApplicationController::class, 'updateStatus']);