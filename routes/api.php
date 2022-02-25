<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', 'UserController@register');

Route::post('/login', 'UserController@login');

Route::middleware(['auth:api'])->group(function () {
    
    Route::post('/create/post', 'PostController@create');

});