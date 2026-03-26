<?php

namespace Database\Seeders;

use App\Models\Carrier;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $chunkSize = 10000;
        $totalCount = 1000000;

        for ($i = 0; $i < $totalCount; $i += $chunkSize) {
            Product::factory($chunkSize)->create();
        }

        for ($i = 0; $i < $totalCount; $i += $chunkSize) {
            Carrier::factory($chunkSize)->create();
        }

    }
}
