<?php

namespace App\Repositories\Post;

use App\Post;
use App\Http\Traits\CacheKeys;

class PostRepository implements PostRepositoryInterface
{
    use CacheKeys;

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

    public function getPostsBy(array $columns = [])
    {
        $cacheKey = CacheKeys::getPostsKey($columns);
        return cache()->remember($cacheKey, env('CACHE_EXPIRE'), function () use ($columns) {
            $posts = $this->post->newQuery();

            foreach ($columns as $column => $value) {
                $posts->where($column, $value);
            }

            return $posts->latest()->paginate(10);
        });
    }
}