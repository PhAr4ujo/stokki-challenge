<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\IRepository;


interface IProductRepository extends IRepository
{
    public function mountDashboardData(): array;
}
