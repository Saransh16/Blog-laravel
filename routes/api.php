<?php

use Illuminate\Http\Request;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {

    Route::post('/posts', [PostController::class, 'create']);

    Route::get('/posts', [PostController::class, 'index']);

    Route::put('/posts/{id}', [PostController::class, 'update']);

    Route::get('/posts/{post}', [PostController::class ,'show']);

    Route::delete('/posts/{post}', [PostController::class, 'delete']);

    Route::put('/posts/{post}/publish',[PostController::class, 'publish']);

    Route::post('posts/{id}/act', [PostController::class, 'like']);

});
