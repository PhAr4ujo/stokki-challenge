<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state (fully dynamic, 1000x unique possible).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['Apple', 'Samsung', 'Sony', 'Dell', 'Nintendo', 'Amazon', 'Microsoft', 'Lenovo', 'HP', 'Asus', 'Xiaomi', 'Logitech', 'LG', 'Acer'];
        $categories = ['Phone', 'Laptop', 'Console', 'Tablet', 'E-reader', 'Headphone', 'Camera', 'Monitor', 'Smartwatch', 'Accessory'];

        $brand = $this->faker->randomElement($brands);
        $category = $this->faker->randomElement($categories);

        $productName = "{$brand} " . ucfirst($category) . ' ' . $this->faker->unique()->bothify('Model-###?' . strtoupper($this->faker->randomLetter));
        $customerNames = ['Amazon', 'Kabum', 'Apple', 'Dell', 'Magazine Luiza', 'Mercado Livre', 'Fast Shop', 'Extra', 'Ponto', 'Carrefour'];
        $customerName = $this->faker->randomElement($customerNames);

        $basePrice = match($category) {
            'Phone' => $this->faker->numberBetween(1000, 10000),
            'Laptop' => $this->faker->numberBetween(2500, 20000),
            'Console' => $this->faker->numberBetween(1500, 8000),
            'Tablet' => $this->faker->numberBetween(800, 10000),
            'E-reader' => $this->faker->numberBetween(250, 1500),
            'Headphone' => $this->faker->numberBetween(200, 3500),
            'Camera' => $this->faker->numberBetween(1000, 15000),
            'Monitor' => $this->faker->numberBetween(600, 8000),
            'Smartwatch' => $this->faker->numberBetween(500, 5000),
            'Accessory' => $this->faker->numberBetween(50, 2000),
            default => $this->faker->numberBetween(500, 12000)
        };

        $amount = $this->faker->numberBetween(1, 100);

        $description = $this->faker->sentence(
            10,
            true
        ) . " Features: " . $this->faker->words($this->faker->numberBetween(3, 6), true) . '.';

        // Generate a created_at date spread among all months of the current year
        $currentYear = now()->year;
        $month = $this->faker->numberBetween(1, 12);
        // Get days in month for safety
        $daysInMonth = Carbon::createFromDate($currentYear, $month, 1)->daysInMonth;
        $day = $this->faker->numberBetween(1, $daysInMonth);

        $createdAt = Carbon::create($currentYear, $month, $day, $this->faker->numberBetween(0, 23), $this->faker->numberBetween(0, 59), $this->faker->numberBetween(0, 59));

        return [
            'name' => $productName,
            'description' => $description,
            'customer_name' => $customerName,
            'price' => $basePrice,
            'amount' => $amount,
            'total' => $basePrice * $amount,
            'created_at' => $createdAt,
        ];
    }
}
