<?php

namespace App\Repositories\UserFollower;

use App\Http\Traits\CacheKeys;
use App\UserFollower;

class UserFollowerRepository implements UserFollowerRepositoryInterface
{
    use CacheKeys;

    private $userFollower;

    public function __construct(UserFollower $userFollower)
    {
        $this->userFollower = $userFollower;
    }

    public function create(array $followData)
    {
        return $this->userFollower->create($followData);
    }

    public function getSingleBy(array $columns = [])
    {
        $follows = $this->userFollower->newQuery();

        foreach ($columns as $column => $value) {
            $follows->where($column, $value);
        }

        $follows = $follows->first();
        return $follows;
    }

    public function getBy(array $columns = [], array $with = [])
    {
        $cacheKey = CacheKeys::getUserFollowerKey($columns);
        return cache()->remember($cacheKey, env('CACHE_EXPIRE'), function () use ($columns, $with) {
            $follows = $this->userFollower->newQuery();

            foreach ($columns as $column => $value) {
                $follows->where($column, $value);
            }

            return $follows->latest()->with($with)->paginate(10);
        });
    }

    public function delete($id)
    {
        $userFollower = $this->getSingleBy(['id', $id]);
        $userFollower->delete();
    }
}
