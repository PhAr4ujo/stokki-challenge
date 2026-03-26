<?php

namespace App\Repositories;

use App\Models\Carrier;
use App\Repositories\Interfaces\ICarrierRepository;
use App\Repositories\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarrierRepository extends Repository implements ICarrierRepository
{
    public function model(): string
    {
        return Carrier::class;
    }

    public function mountDashboardData(): array
    {
        $amountOfCarriers = Carrier::count();
        
        return [
            'amount_of_carriers' => $amountOfCarriers,
        ];
    }
}
