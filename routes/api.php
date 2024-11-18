<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('tasks', App\Http\Controllers\Task\TaskController::class)
    ->middleware('auth:sanctum');

Route::post('/register', [AuthenticatedSessionController::class, 'register']);
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])
    ->middleware('auth:sanctum');
