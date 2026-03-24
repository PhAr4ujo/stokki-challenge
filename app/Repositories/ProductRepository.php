<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\IProductRepository;
use App\Repositories\Repository;

class ProductRepository extends Repository implements IProductRepository
{
    public function model(): string
    {
        return Product::class;
    }
}
