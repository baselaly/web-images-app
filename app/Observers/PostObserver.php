<?php

namespace App\Observers;

use App\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function deleted(Post $post)
    {
        $postImages = $post->images;
        foreach ($postImages as $postImage)
            Storage::disk('images')->delete($postImage->getOriginal('image'));
    }
}
