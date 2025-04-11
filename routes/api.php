<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
});

// Task Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->where('task', '[0-9]+');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->where('task', '[0-9]+');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->where('task', '[0-9]+');
    // comment routes
    Route::get('/tasks/{task}/comments', [CommentController::class, 'index'])->where('task', '[0-9]+');
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->where('task', '[0-9]+');
    // time log routes
    // Route::post('/tasks/{task}/time-logs', [\App\Http\Controllers\TimeLogController::class, 'store']);
    // Route::get('/tasks/{task}/time-logs', [\App\Http\Controllers\TimeLogController::class, 'index']);
    // file upload routes
    // Route::post('/tasks/{task}/files', [\App\Http\Controllers\FileController::class, 'store']);
    // Route::get('/tasks/{task}/files', [\App\Http\Controllers\FileController::class, 'index']);
});
