<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            \App\CoffeeR\Domain\TweetRepositoryInterface::class,
            \App\CoffeeR\Repository\TweetApiRepository::class
        );
        $this->app->bind(
            \App\CoffeeR\Domain\TwitterTokenRepositoryInterface::class,
            \App\CoffeeR\Repository\TwitterTokenApiRepository::class
        );
    }
}
