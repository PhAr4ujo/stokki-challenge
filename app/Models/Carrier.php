<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'street',
        'cnpj',
        'city',
        'neighborhood',
        'residence_number',
        'complement',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Database\Factories\CarrierFactory
     */
    protected static function newFactory()
    {
        return \Database\Factories\CarrierFactory::new();
    }
}
