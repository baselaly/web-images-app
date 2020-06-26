<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Notifications\SendNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\User;

class PostCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        $post = $event->post;
        $followers = User::whereIn('id', $post->user->followers->pluck('follower_id')->toArray())->get();
        $body = $post->user->fullname . " Publish New Post";
        Notification::send($followers, (new SendNotification($body))->delay(Carbon::now()->addSeconds(2)));
    }
}
