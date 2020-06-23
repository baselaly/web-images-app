<?php

namespace App\Observers;

use App\Like;
use App\Notifications\SendNotification;
use Carbon\Carbon;

class LikeObserver
{
    public function created(Like $like)
    {
        $likeable = $like->likeable;
        if ($likeable instanceof \App\Post && $like->user_id != $likeable->user_id) {
            $body = $like->user->fullname . " Liked Your Post";
            $notification = (new SendNotification($body))->delay(Carbon::now()->addSeconds(5));
            $like->user->notify($notification);
        }
    }
}
