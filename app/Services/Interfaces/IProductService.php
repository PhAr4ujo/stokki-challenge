<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\IService;

interface IProductService extends IService
{
    public function mountDashboardData(): array;
    public function exportData();
}
