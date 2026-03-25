<?php

namespace App\Repositories;

use App\Models\Carrier;
use App\Repositories\Interfaces\ICarrierRepository;
use App\Repositories\Repository;

class CarrierRepository extends Repository implements ICarrierRepository
{
    public function model(): string
    {
        return Carrier::class;
    }
}
