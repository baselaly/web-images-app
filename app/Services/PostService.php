<?php

namespace App\Services;

use App\Repositories\Post\PostRepositoryInterface;

class PostService
{
    private $post;

    public function __construct(PostRepositoryInterface $post)
    {
        $this->post = $post;
    }
}
