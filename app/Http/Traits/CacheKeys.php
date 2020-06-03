<?php

namespace App\Http\Traits;

trait CacheKeys
{
    public static function getPostsKey(array $columns = [], array $userIds = []): string
    {
        $page = request('page') ?: 1;
        $cacheKey = 'posts';

        foreach ($columns as $key => $value) {
            $cacheKey = $cacheKey . '.' . $key . '.' . $value;
        }

        if (count($userIds)) {
            $cacheKey = $cacheKey . '.homePage.' . end($userIds);
        }

        $cacheKey = $cacheKey . '.page.' . $page;

        return $cacheKey;
    }

    public static function getUserFollowerKey(array $columns = []): string
    {
        $page = request('page') ?: 1;
        $cacheKey = 'userFollowers';

        foreach ($columns as $key => $value) {
            $cacheKey = $cacheKey . '.' . $key . '.' . $value;
        }

        $cacheKey = $cacheKey . '.page.' . $page;

        return $cacheKey;
    }
}
