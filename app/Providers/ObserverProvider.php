<?php

namespace App\Providers;

use App\Observers\PostImageObserver;
use App\PostImage;
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
        PostImage::observe(new PostImageObserver());
    }
}
