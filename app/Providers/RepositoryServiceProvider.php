<?php

namespace App\Providers;

use App\Repositories\Post\PostRepository;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\UserFollower\UserFollowerRepository;
use App\Repositories\UserFollower\UserFollowerRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(UserFollowerRepositoryInterface::class, UserFollowerRepository::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
