<?php
namespace App\Http\Traits;

trait CacheKeys
{
    public static function getProfilePostsKey($userId): string
    {
        $page = request('page') ?: 1;
        return "user.posts.$userId.$page";
    }
}
