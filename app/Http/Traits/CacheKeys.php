<?php

namespace App\Http\Traits;

trait CacheKeys
{
    public static function getPostsKey($columns): string
    {
        $page = request('page') ?: 1;
        $cacheKey = 'posts';

        foreach ($columns as $key => $value) {
            $cacheKey = $cacheKey . '.' . $key . '.' . $value;
        }

        $cacheKey = $cacheKey . '.page.' . $page;

        return $cacheKey;
    }
}
