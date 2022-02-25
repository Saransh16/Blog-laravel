<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function create()
    {
        $inputs = request()->all();

        $validate=Validator::make($inputs,[
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'required',
            'user_id' => 'required'
        ]);

        if($validate->fails())
        {
            return response()->json([
                'code' => 422,
                'data' => [],
                'errors' => $validate->errors()->messages()
            ]);
        }

        // dd(auth()->user());

        $post = Post::create([
            'title' => $inputs['title'],
            'content' => $inputs['content'],
            'is_published' => $inputs['is_published'],
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'code' => 200,
            'data' => [$post],
            'errors' => []
        ]);
    }
}
