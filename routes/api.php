<?php

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks/{task}', [TaskController::class, 'show']);
Route::get('/tasks/{task}/comments', [\App\Http\Controllers\CommentController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::post('/tasks/{task}/comments', [\App\Http\Controllers\CommentController::class, 'store']);
});

Route::get('/stats', [TaskController::class, 'stats']);

Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);
    Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);
});

Route::get('/get-comments', function () {
   $comments = Comment::where('user_id', 1)->with(['task:id,title', 'user:id,name'])->get();
   return response()->json($comments);
});
