<?php

namespace App\Repositories\Post;

use App\Post;

class PostRepository implements PostRepositoryInterface
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function create($postData, $postImages)
    {
        $post = $this->post->create($postData);
        $post->images()->createMany($postImages);
    }
}
