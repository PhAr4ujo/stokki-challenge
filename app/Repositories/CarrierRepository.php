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

    public function listRecords(array $filters, $paginationAmount): LengthAwarePaginator
    {
        $query = $this->model->query()->orderBy('name');

        $name = $filters['name'] ?? null;
        if (filled($name)) {
            $term = '%'.addcslashes($name, '%_\\').'%';
            $query->where('name', 'like', $term);
        }

        return $query->paginate($paginationAmount);
    }
}
