<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function updated(User $user)
    {
        if ($user->isDirty('image')) {
            $newImage = $user->image;
            $oldImage = $user->getOriginal('image');
            dd($newImage, $oldImage);
        }
    }
}
