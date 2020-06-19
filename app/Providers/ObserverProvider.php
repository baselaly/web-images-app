<?php

namespace App\Providers;

use App\Like;
use App\Observers\LikeObserver;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use App\Post;
use App\User;
use Illuminate\Support\ServiceProvider;

class ObserverProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Post::observe(new PostObserver());
        User::observe(new UserObserver());
        Like::observe(new LikeObserver());
    }
}
