<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'customer_name',
        'price',
        'amount',
    ];

    protected static function booted()
    {
        static::saving(function ($product) {
            $product->total = $product->price * $product->amount;
        });
    }
}
