<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    public function updated(User $user)
    {
        if ($user->isDirty('image')) {
            $oldImage = $user->getOriginal('image');
            if ($oldImage) {
                Storage::disk('users')->delete($oldImage);
            }
        }
    }
}
