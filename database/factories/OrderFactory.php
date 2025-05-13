<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'status' => fake()->randomElement(['pending', 'shipped', 'delivered']),
            'invoice_number' => fake()->unique()->randomNumber(8),
            'invoice_date' => fake()->dateTime(),
            'total' => 0
        ];
    }
}
