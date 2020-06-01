<?php

namespace App\Observer;

use App\PostImage;
use Illuminate\Support\Facades\Storage;

class PostImageObserver
{
    public function deleted(PostImage $postImage)
    {
        Storage::disk('images')->delete($postImage->getOriginal('image'));
    }
}
