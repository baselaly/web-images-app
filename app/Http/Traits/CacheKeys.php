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
            // specify that cached home page for that authenticated user as we pushed its id at the end of array in service
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
