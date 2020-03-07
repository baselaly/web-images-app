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

    public function create(array $postData, array $postImages)
    {
        $post = $this->post->create($postData);
        $post->images()->createMany($postImages);
        return $post;
    }

    public function getPosts(string $cacheKey, array $columns = [])
    {
        return cache()->remember($cacheKey, env('CACHE_EXPIRE'), function () use ($columns) {
            $posts = $this->post->newQuery();

            foreach ($columns as $column => $value) {
                $posts->where($column, $value);
            }

            return $posts->active()->latest()->paginate(10);
        });
    }
}
