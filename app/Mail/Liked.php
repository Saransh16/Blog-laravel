<?php

namespace App\Mail;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Liked extends Mailable
{
    use Queueable, SerializesModels;
    public $like;
    public $like_count;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Like $like, $like_count)
    {
        $this->like = $like;
        $this->like_count = $like_count;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('your post got a like')
            ->view('emails.PostLike');
    }
}
