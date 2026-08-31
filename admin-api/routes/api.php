<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Welcome Moji!';
});

Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);