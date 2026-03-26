<?php

namespace App\Services;

use App\Repositories\Interfaces\ICarrierRepository;
use App\Services\Interfaces\ICarrierService;
use App\Services\Service;

class CarrierService extends Service implements ICarrierService
{
 
    public function __construct(
        ICarrierRepository $repository,
    ) {
        parent::__construct($repository);
    }

    public function insert($data)
    {
        \Cache::forget('dash_agg');
        \Cache::forget('dash_unique_customers');
        \Cache::forget('dash_top_products');
        \Cache::forget('dash_top_customers');
        \Cache::forget('dash_monthly_sales');
        return parent::insert($data);
    }

    public function edit($id, $data)
    {
        \Cache::forget('dash_agg');
        \Cache::forget('dash_unique_customers');
        \Cache::forget('dash_top_products');
        \Cache::forget('dash_top_customers');
        \Cache::forget('dash_monthly_sales');
        return parent::edit($id, $data);
    }

    public function delete($id)
    {
        \Cache::forget('dash_agg');
        \Cache::forget('dash_unique_customers');
        \Cache::forget('dash_top_products');
        \Cache::forget('dash_top_customers');
        \Cache::forget('dash_monthly_sales');
        return parent::delete($id);
    }

    public function mountDashboardData(): array
    {
        return $this->repository->mountDashboardData();
    }
}
