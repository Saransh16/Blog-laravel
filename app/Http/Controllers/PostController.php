<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Mail\Liked;

use Mail;

use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function create()
    {
        $inputs = request()->all();

        $validate = Validator::make($inputs, [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->error($validate->errors()->messages());
        }

        $post = Post::create([
            'title' => $inputs['title'],
            'content' => $inputs['content'],
            'is_published' => $inputs['is_published'],
            'user_id' => auth()->user()->id,
        ]);
        return response()->success($post);
    }

    // public function index()
    // {
    //     $posts = Post::all();

    //     return $posts;
    // }

    public function index()
    {
        $posts = Post::where('user_id', '=', auth()->user()->id)->paginate(5);

        return $posts;
    }

    public function update($id)
    {
        $inputs = request()->all();

        $validate = Validator::make($inputs, [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->error($validate->errors()->messages());
        }

        $post = Post::where('id', $id)->first();

        if (!$post) {
            return response()->error('post not found', 400);
        }

        $this->authorize('update', $post);

        if (auth()->user()->id !== $post->user_id) {
            return response()->error('not authorized', 401);
        }

        $inputs = request()->all();

        $validate = Validator::make($inputs, [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'is_published' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->error($validate->errors()->messages());
        }

        $post->update([
            'title' => $inputs['title'],
            'content' => $inputs['content'],
            'is_published' => $inputs['is_published'],
        ]);

        return response()->success($post);
    }

    public function show(User $user, Post $post)
    {
        if ($user->id !== $post->user_id) {
            return response()->error('post not found', 400);
        }

        return response()->success($post);
    }

    public function delete(User $user, Post $post)
    {
        if ($user->id !== $post->user_id) {
            return response()->error('post not found', 400);
        } else {
            $post->delete();
            return response()->success($message = 'post deleted Successfully'); // Add message here.
        // }
    }

    public function publish($user_id, $post_id)
    {
        $post = Post::where('id', $post_id)->first();

        $this->authorize('publish', $post);

        $inputs = request()->all();

        $validate = Validator::make($inputs, [
            'is_published' => 'required|boolean',
        ]);

        if ($validate->fails()) {
            return response()->error($validate->errors()->messages());
        }

        // $post->update($inputs);
        $post->update([
            'is_published' => $inputs['is_published'],
        ]);
        // $post->save();

        return response()->success($post);
    }

    public function like($id)
    {
        $post = Post::where('id', $id)->first();

        //check if post exists
        if (!$post) {
            return response()->error('post not found', 400);
        }

        // // check if the user has not already liked the post
        $like = Like::where('user_id' , '=', auth()->user()->id)
                    ->where('post_id', '=', $post->id)
                    ->first();
        
        if($like){
            return response()->error('Post already liked', 400);            
        } 

        $like = Like::create([
                
            'post_id' => $post->id,  
            'user_id' => auth()->user()->id,
            
        ]);

        //fetch user object from the post user id and then use user email

        $user = User::where('id', '=', $post->user_id)
                            ->first();

        // dd($user->email);
        // dd(User::where('id', '=', $post->user_id)->toSql());
        
        $like_count = Like::where('post_id', '=', $post->id)->count();
        
        Mail::to($user->email)->send(new Liked($like, $like_count));
        return response()->success(['message' => 'Post liked successfuly']);
       
        
        
    }
}
