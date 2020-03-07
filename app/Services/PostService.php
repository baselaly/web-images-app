<?php

namespace App\Services;

use App\Http\Traits\CacheKeys;
use App\Repositories\Post\PostRepositoryInterface;

class PostService
{
    use CacheKeys;
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
        $cacheKey = CacheKeys::getProfilePostsKey($userId);
        return $this->post->getPosts($cacheKey, ['user_id' => $userId]);
    }
}
