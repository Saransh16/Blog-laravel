<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id;
        // return $user->id === $post->user_id
        //         ? Response::allow()
        //         : Response::deny('You do not own this post.');
    }

    public function publish(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}
