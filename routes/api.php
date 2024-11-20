<?php

use App\Http\Controllers\Admin\Tag\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Category\CategoryController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('tasks', App\Http\Controllers\Task\TaskController::class)
    ->middleware('auth:sanctum');

Route::prefix('/admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
});

Route::post('/register', [AuthenticatedSessionController::class, 'register']);
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::get('/categories/list', [CategoryController::class, 'index'])
    ->middleware('auth:sanctum');

Route::get('/tasks/list', [TaskController::class, 'index']);
