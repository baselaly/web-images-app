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

    public function getHomePosts($user)
    {
        $followingIds = $user->followings->pluck('user_id')->toArray();
        array_push($followingIds, $user->id);
        return $this->post->getPostsBy(['active' => 1], $followingIds);
    }

    public function getSinglePost($id)
    {
        return $this->post->getSinglePostBy(['id' => $id]);
    }

    public function checkPostView($post)
    {
        if (auth('api')->check()) {
            $user = auth('api')->user();
            if ($user->id == $post->user_id) {
                return true;
            } elseif ($user->id != $post->user_id && $post->active == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return $post->active == 1 ? true : false;
        }
    }

    public function delete($post)
    {
        $this->post->delete($post);
    }
}
