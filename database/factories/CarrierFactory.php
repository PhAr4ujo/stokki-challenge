<?php

namespace Database\Factories;

use App\Models\Carrier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Carrier>
 */
class CarrierFactory extends Factory
{
    protected $model = Carrier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $states = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
            'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        ];

        $cnpj_digits = $this->faker->numerify(str_repeat('#', 14));

        return [
            'name' => $this->faker->company . ' Transportes',
            'state' => $this->faker->randomElement($states),
            'street' => $this->faker->streetName(),
            'cnpj' => $cnpj_digits,
            'city' => $this->faker->city(),
            'neighborhood' => $this->faker->streetSuffix(),
            'residence_number' => $this->faker->buildingNumber(),
            // Guarantee 'complement' is a non-null string (can be empty)
            'complement' => $this->faker->secondaryAddress() ?? '',
        ];
    }
}