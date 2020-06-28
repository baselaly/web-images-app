<?php

namespace App\Repositories\Post;

interface PostRepositoryInterface
{
    public function create(array $postData, array $postImages);
    public function getPostsBy(array $filters = [], int $paginate = 0);
    public function getSinglePostBy(array $filters = []);
    public function delete(int $id);
    public function update(int $id, array $postData);
}
