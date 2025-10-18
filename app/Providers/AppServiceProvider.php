<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CategoryService;
use App\Services\CachedCategoryServiceProxy;
use App\Services\Interfaces\CategoryServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CategoryService::class, function ($app) {
            return new CategoryService();
        });

        $this->app->singleton(CategoryServiceInterface::class, function ($app) {
            $real = $app->make(CategoryService::class);
            return new CachedCategoryServiceProxy($real, 3600); // TTL в секундах
        });
    }

    public function boot(): void
    {
        //
    }
}
