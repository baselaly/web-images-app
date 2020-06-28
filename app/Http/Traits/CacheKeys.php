<?php

namespace App\Http\Traits;

trait CacheKeys
{
    public static function getPostsKey(array $filters = []): string
    {
        $page = request('page') ?: 1;
        $cacheKey = 'posts';

        foreach ($filters as $key => $value) {
            if ($key === 'user_ids') {
                count($value) === 1 ? $cacheKey .= '.user_id.' . end($value)
                    : $cacheKey .= '.homePage.' . end($value);
                continue;
            }
            $cacheKey .= '.' . $key . '.' . $value;
        }

        $cacheKey .= '.page.' . $page;

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
