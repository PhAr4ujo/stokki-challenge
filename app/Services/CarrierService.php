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

    public function mountDashboardData(): array
    {
        return $this->repository->mountDashboardData();
    }
}
