<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Validator;
use Hash;
use App\Models\User;
use Auth;
use App\Mail\Welcome;
use Mail;



class AuthController extends Controller
{
    public function register()
    {
        $inputs = request()->all();

        //validate 
        $validator=Validator::make($inputs,[
            'first_name' => 'required|string|max:255',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);


        // if not validated return response with error
        if($validator->fails()) 
        {
            return response()->json([
                'code' => 422,
                'data' => [],
                'errors' => $validator->errors()->messages() //validation error messages will go here
            ]);
        }

        $user = User::create([
            'first_name' => $inputs['first_name'],
            'last_name' => $inputs['last_name'],
            'email' => $inputs['email'],
            'password' => Hash::make($inputs['password']),
        ]);

        Mail::to($user->email)->send(new Welcome($user));

        $accessToken = $user->createToken('authToken')->accessToken;

        // if validated enter details to database

        

        return response()->json([
            'code' => 200,
            'data' => [$user, 'access_token' => $accessToken],
            'errors' => []
        ]);

      
        
    }

    public function login()
    {
        $inputs = request()->all();

        $validator=Validator::make($inputs,[
            'email' => 'required|email',
            'password' => 'required',
        ]);

         // if not validated return response with error
        if($validator->fails()) 
        {
            return response()->json([
                'code' => 422,
                'data' => [],
                'errors' => $validator->errors()->messages() //validation error messages will go here
            ]);
        }

        $user = User::where('email', $inputs['email'])->first();

        if(!$user) 
        {
            return response()->json([
                'code' => 400,
                'data' =>[],
                'errors' => 'Invalid Credentials'
            ]);
        }

        if(Hash::check($inputs['password'], $user->password)) {
            $token = $user->createToken('authToken')->accessToken;

            return response()->json([
                'code'=> 200,
                'data' => ['user' => $user, 'access_token' => $token],
                'errors'=> []
            ]);
        } else {
            return response()->json([
                'code' => 400,
                'data' =>[],
                'errors' => 'Invalid Credentials'
            ]);
        }


    }
}
