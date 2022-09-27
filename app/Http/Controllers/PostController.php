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
        ]);

        if($validate->fails())
        {
  
            return response()->error($validate->errors()->messages());
        }

        // dd(auth()->user());

        $post = Post::create([
            'title' => $inputs['title'],
            'content' => $inputs['content'],
            'is_published' => $inputs['is_published'],
            'user_id' => auth()->user()->id
        ]);
        return response()->success($post);
    }

    public function index()
    {
        $posts = Post::all();

        return $posts;
    } 
    
    public function userPost()
    {
        $posts = Post::where('user_id', '=', auth()->user()->id)->get();

        return $posts;
    }

    public function update($id)
    {   

        $inputs = request()->all();

        $validate=Validator::make($inputs,[
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'required',
        ]);

        if($validate->fails())
        {
            return response()->error($validate->errors()->messages());
        }


        $post = Post::where('id', $id)->first();
        
        if(!$post) {
            return response()->error('post not found', 400);
        }

        $this->authorize('update', $post);

        if(auth()->user()->id !== $post->user_id) {
            return response()->error('not authorized', 401);
        }
        
        $inputs = request()->all();

        $validate=Validator::make($inputs,[
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'required',
        ]);

        if($validate->fails())
        {
            return response()->error($validate->errors()->messages());
        }
        
        $post->update([
            'title' => $inputs['title'],
            'content' => $inputs['content'],
            'is_published' => $inputs['is_published']
        ]);
        
        return response()->success($post);
    

    }


    public function show(User $user, Post $post)
    {
        if($user->id !== $post->user_id) {
            return response()->error('post not found', 400);
        }

        return response()->success($post);
    }

    public function delete(User $user, Post $post)
    {
        if($user->id !== $post->user_id) {

            return response()->error('post not found', 400);
        }
        else {
            $post->delete();
            return response()->success($message = 'post deleted Successfully');  // Add message here. 
        }

    }


    public function publish($user_id, $post_id)
    {
        $post = Post::where('id', $post_id)->first();

        if(auth()->user()->id != $post->user_id) {

            return response()->error('post not found', 400);
        }

        $inputs = request()->all();

        $validate=Validator::make($inputs,[
            'is_published' => 'required|boolean',
        ]);

        
        dd($post);
            
        $post->update($inputs);
        $post->save();

        return response()->success($post);
        
    }
}
