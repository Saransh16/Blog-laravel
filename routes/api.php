<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', 'AuthController@register');

Route::post('/login', 'AuthController@login');

Route::middleware(['auth:api'])->group(function () {
    
    Route::post('/create/post', 'PostController@create');

    Route::get('/posts', 'PostController@index');

    Route::get('/posts/user', 'PostController@userPost');

    Route::put('/update/post', 'PostController@update');

});