<?php

namespace App\Providers;

use App\Packages\Cart\Domain\CartFactoryInterface;
use App\Packages\Cart\Domain\CartProductFactoryInterface;
use App\Packages\Cart\Domain\CartRepositoryInterface;
use App\Packages\Cart\Infrastructure\CartFactory;
use App\Packages\Cart\Infrastructure\CartProductFactory;
use App\Packages\Cart\Infrastructure\CartRepository;
use App\Packages\Catalog\Domain\LargeCategoryListRepositoryInterface;
use App\Packages\Catalog\Domain\ProductRepositoryInterface;
use App\Packages\Catalog\Infrastructure\LargeCategoryListRepository;
use App\Packages\Catalog\Infrastructure\ProductOutlineQueryService;
use App\Packages\Catalog\Infrastructure\ProductRepository;
use App\Packages\Catalog\Usecase\ProductOutlineQueryServiceInterface;
use App\Packages\User\Domain\UserFactoryInterface;
use App\Packages\User\Domain\UserRepositoryInterface;
use App\Packages\User\Infrastructure\UserFactory;
use App\Packages\User\Infrastructure\UserRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(UserFactoryInterface::class, UserFactory::class);

        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(LargeCategoryListRepositoryInterface::class, LargeCategoryListRepository::class);
        $this->app->singleton(ProductOutlineQueryServiceInterface::class, ProductOutlineQueryService::class);

        $this->app->singleton(CartRepositoryInterface::class, CartRepository::class);
        $this->app->singleton(CartFactoryInterface::class, CartFactory::class);
        $this->app->singleton(CartProductFactoryInterface::class, CartProductFactory::class);
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
