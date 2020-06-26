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

    public function getPostsBy(array $columns = [], array $userIds = [])
    {
        $cacheKey = CacheKeys::getPostsKey($columns, $userIds);
        return cache()->remember($cacheKey, env('CACHE_EXPIRE'), function () use ($columns, $userIds) {
            $posts = $this->post->newQuery();

            foreach ($columns as $column => $value) {
                $posts->where($column, $value);
            }

            if (count($userIds)) {
                $posts->whereIn('user_id', $userIds);
            }

            return $posts->latest()->paginate(20);
        });
    }

    public function getSinglePostBy(array $columns = [])
    {
        $post = $this->post->newQuery();

        foreach ($columns as $column => $value) {
            $post->where($column, $value);
        }

        return $post->first();
    }

    public function delete(int $id)
    {
        return $this->post->where('id', $id)->delete();
    }

    public function update(int $id, array $postData)
    {
        return $this->post->where('id', $id)->update($postData);
    }
}
