<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API resources
Route::apiResource('events', EventController::class);
Route::apiResource('events.attendees', AttendeeController::class)
    ->scoped()->except(['update']);

// API auth
Route::post('/login', [AuthController::class, 'login'])
    ->name('auth.login');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
    ->name('auth.logout');
