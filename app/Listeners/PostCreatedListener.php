<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Notifications\SendNotification;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class PostCreatedListener
{
    protected $userService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
        $body = $post->user->fullname . " Publish New Post";
        $followers = $this->userService->getUserFollowers($post->user_id);
        dd($followers->toArray());
        Notification::send($followers, (new SendNotification($body))->delay(Carbon::now()->addSeconds(2)));
    }
}
