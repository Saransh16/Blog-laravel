<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Validation\Rules\Password;

use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    public function register()
    {
        $inputs = request()->all();

        // dd($inputs);

        //validate 
        $validator=Validator::make($inputs,[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);


        // if not validated return response with error
        if($validator->fails()) 
        {
            return response()->error($validator->error()->messages(),422);
        }

        return response()->json([
            'code' => 422,
            'data' => [],
            'errors' => [] //validation error messages will go here
        ]);

        // if validated enter details to database

        return response()->json([
            'code' => 200,
            'data' => 'User registered successfully',
            'errors' => []
        ]);
    }
}
