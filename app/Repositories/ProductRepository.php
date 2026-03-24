<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\IProductRepository;
use App\Repositories\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository extends Repository implements IProductRepository
{
    public function model(): string
    {
        return Product::class;
    }
}
