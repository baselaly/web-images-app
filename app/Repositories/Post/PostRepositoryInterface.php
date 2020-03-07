<?php

namespace App\Repositories\Post;

interface PostRepositoryInterface
{
    public function create(array $postData, array $postImages);
    public function getPosts(string $cacheKey, array $columns = []);
}
