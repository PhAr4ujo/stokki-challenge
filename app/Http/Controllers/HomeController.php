<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\ICarrierService;
use App\Services\Interfaces\IProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct
    (
        protected IProductService $iProductService,
        protected ICarrierService $iCarrierService,
    )
    {

    }
    public function index()
    {
        $data = $this->iProductService->mountDashboardData();
        $data += $this->iCarrierService->mountDashboardData();

        return Inertia::render('Dashboard', [
            'data' => $data
        ]);
    }
}
