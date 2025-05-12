<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $states = ["Mexico,", "Guadalajara", "Monterrey", "Puebla", "Tijuana", "Leon", "Cancun", "Merida", "Veracruz", "Toluca", "Hermosillo", "Chihuahua", "Saltillo", "Durango", "Aguascalientes", "San Luis Potosi", "Oaxaca", "Morelia", "Culiacan", "Mazatlan"];
        return [
            'street' => fake()->streetAddress(),
            'city' => fake()->city(),
            'zip_code' => fake()->postcode(),
            'state' => fake()->randomElement($states),
            'colony' => fake()->word(),
            'country' => fake()->country(),
            'external_number' => fake()->buildingNumber(),
        ];
    }
}
