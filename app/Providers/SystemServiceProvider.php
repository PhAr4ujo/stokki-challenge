<?php

namespace App\Providers;

use App\Services\CarrierService;
use App\Services\Interfaces\ICarrierService;
use App\Services\Interfaces\IProductService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IProductService::class, ProductService::class);
        $this->app->bind(ICarrierService::class, CarrierService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
