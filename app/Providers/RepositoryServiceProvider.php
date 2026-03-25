<?php

namespace App\Providers;

use App\Repositories\CarrierRepository;
use App\Repositories\Interfaces\ICarrierRepository;
use App\Repositories\Interfaces\IProductRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(ICarrierRepository::class, CarrierRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
