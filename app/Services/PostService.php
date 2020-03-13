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

    public function createPost($userId)
    {
        $postData = request()->merge(['user_id' => $userId])->toArray();
        $postImages = array();

        foreach (request('images') as $image) {
            $postImages[] = ['image' => $image];
        }

        $this->post->create($postData, $postImages);
    }

    public function getUserPosts($userId)
    {
        return $this->post->getPostsBy(['user_id' => $userId]);
    }

    public function getUserActivePosts($userId)
    {
        return $this->post->getPostsBy(['user_id' => $userId, 'active' => 1]);
    }
}
