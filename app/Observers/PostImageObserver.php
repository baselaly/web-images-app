<?php

namespace App\Observers;

use App\PostImage;
use Illuminate\Support\Facades\Storage;

class PostImageObserver
{
    public function deleted(PostImage $postImage)
    {
        dd("Dasd");
        Storage::disk('images')->delete($postImage->getOriginal('image'));
    }
}
