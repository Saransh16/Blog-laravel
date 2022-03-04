<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function create()
    {
        $inputs = request()->all();

        $validate=Validator::make($inputs,[
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'required',
            // 'user_id' => 'required'   //remove this
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

    public function index()
    {
        $posts = Post::all();

        return $posts;
    } 
    
    public function userPost()
    {
        $posts = Post::where('user_id', '=', auth()->user()->id)->get();
        // dd($posts);

        return $posts;
    }

    public function update(User $user, Post $post)
    {   
        if(auth()->user()->id !== $post->user_id) {
            return error;
        }
        else {
            $inputs = request()->all();

            $validate=Validator::make($inputs,[
                'title' => 'required|string|max:255',
                'content' => 'required',
                'is_published' => 'required',
            ]);
            // dd($inputs);
            // $post = Post::create([
            //     'title' => $inputs['title'],
            //     'content' => $inputs['content'],
            //     'is_published' => $inputs['is_published'],
            //     'user_id' => auth()->user()->id
            // ]);
    
            $post->update($inputs);
    
            return response()->json([
                'code' => 200,
                'data' => [$post],
                'errors' => []
            ]);
        }


    }


    public function show(User $user, Post $post)
    {
        if($user->id !== $post->user_id) {
            return response()->json([
                'code' => 400,
                'data' => [],
                'errors' => 'Post not Found'
            ], 400);
        }

        // $posts = Post::where('id', 1)->get();

        // dd($post);
        return response()->json([
            'code' => 200,
            'data' => $post,
            'errors' => []
        ], 200);
    }

    public function delete(User $user, Post $post)
    {
        if($user->id !== $post->user_id) {
            return response()->json([
                'code' => 400,
                'data' => [],
                'errors' => 'Post not Found'
            ], 400);
        }
        else {
            $post->delete();
            return response()->json([
                'code' => 200,
                'data' => ['message'=>'post deleted successfully!'],
                'errors' => []
            ], 200);
        }



    }
}
