<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\IService;

interface ICarrierService extends IService
{
    public function mountDashboardData(): array;
}
