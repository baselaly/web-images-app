<?php

namespace App\Repositories\Post;

use App\Post;

interface PostRepositoryInterface
{
    public function create(array $postData, array $postImages);
    public function getPostsBy(array $columns = [], array $userIds = []);
    public function getSinglePostBy(array $columns = []);
    public function delete(Post $post);
}
