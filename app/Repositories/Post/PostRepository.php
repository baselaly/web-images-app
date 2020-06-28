<?php

namespace App\Repositories\Post;

use App\Post;
use App\Http\Traits\CacheKeys;
use App\QueryFilters\Post\ActiveFilter;
use App\QueryFilters\Post\KeywordFilter;
use App\QueryFilters\Post\UserIdsFilter;
use Illuminate\Pipeline\Pipeline;

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

    public function getPostsBy(array $filters = [], int $paginate = 0)
    {
        $cacheKey = CacheKeys::getPostsKey($filters);
        dd($cacheKey);
        return cache()->remember($cacheKey, env('CACHE_EXPIRE'), function () use ($filters, $paginate) {
            $posts = app(Pipeline::class)
                ->send($this->post->query())
                ->through([
                    new UserIdsFilter($filters),
                    new ActiveFilter($filters),
                    new KeywordFilter($filters)
                ])
                ->thenReturn();

            $posts = $paginate && $paginate !== 0 ? $posts->paginate($paginate) : $posts->get();
            return $posts;
        });
    }

    public function getSinglePostBy(array $filters = [])
    {
        return app(Pipeline::class)
            ->send($this->post->query())
            ->through([
                new UserIdsFilter($filters),
                new ActiveFilter($filters),
                new KeywordFilter($filters),
            ])
            ->thenReturn()->first();
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
