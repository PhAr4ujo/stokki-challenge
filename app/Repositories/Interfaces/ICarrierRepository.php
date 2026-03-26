<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\IRepository;


interface ICarrierRepository extends IRepository
{
    public function mountDashboardData(): array;
}
