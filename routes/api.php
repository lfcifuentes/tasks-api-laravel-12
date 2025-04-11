<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    // Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index']);
    // Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store']);
    // Route::get('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'show']);
    // Route::put('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'update']);
    // Route::delete('/tasks/{task}', [\App\Http\Controllers\TaskController::class, 'destroy']);
    // comment routes
    // Route::post('/tasks/{task}/comments', [\App\Http\Controllers\CommentController::class, 'store']);
    // Route::get('/tasks/{task}/comments', [\App\Http\Controllers\CommentController::class, 'index']);
    // time log routes
    // Route::post('/tasks/{task}/time-logs', [\App\Http\Controllers\TimeLogController::class, 'store']);
    // Route::get('/tasks/{task}/time-logs', [\App\Http\Controllers\TimeLogController::class, 'index']);
    // file upload routes
    // Route::post('/tasks/{task}/files', [\App\Http\Controllers\FileController::class, 'store']);
    // Route::get('/tasks/{task}/files', [\App\Http\Controllers\FileController::class, 'index']);
});
