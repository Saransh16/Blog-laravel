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
    Route::post('/create/post', [PostController::class, 'create']);

    Route::get('/posts', [PostController::class, 'userPost']);

    Route::put('/posts/{id}', [PostController::class, 'update']);

    Route::get('/users/{user}/posts/{post}', [PostController::class ,'show']);

    Route::delete('/users/{user}/posts/{post}', [PostController::class, 'delete']);

    Route::put(
        '/users/{user}/posts/{posts}/published',
        'PostController@publish'
    );
});
