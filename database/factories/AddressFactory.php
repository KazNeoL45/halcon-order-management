<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\State;
use App\Models\Country;

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
        // pick a random existing state and country (seeded prior to orders)
        $state = State::inRandomOrder()->first();
        $country = Country::inRandomOrder()->first();
        return [
            'street'          => fake()->streetAddress(),
            'external_number' => fake()->buildingNumber(),
            'colony'          => fake()->word(),
            'city'            => fake()->city(),
            'state_id'        => $state ? $state->id : null,
            'zip_code'        => fake()->postcode(),
            'country_id'      => $country ? $country->id : null,
        ];
    }
}
