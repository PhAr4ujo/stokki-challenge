<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
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
}
