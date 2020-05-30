<?php

namespace App\Repositories\Post;

interface PostRepositoryInterface
{
    public function create(array $postData, array $postImages);
    public function getPostsBy(array $columns = []);
    public function getSinglePostBy(array $columns = []);
}
