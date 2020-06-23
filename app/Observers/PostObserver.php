<?php

namespace App\Observers;

use App\Notifications\SendNotification;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function deleted(Post $post)
    {
        $postImages = $post->images;
        foreach ($postImages as $postImage)
            Storage::disk('images')->delete($postImage->getOriginal('image'));
    }

    public function created(Post $post)
    {
        $followers = User::whereIn('id', $post->user->followers->pluck('follower_id')->toArray())->get();
        $body = $post->user->fullname . " Publish New Post";
        Notification::send($followers, (new SendNotification($body))->delay(Carbon::now()->addSeconds(2)));
    }
}
