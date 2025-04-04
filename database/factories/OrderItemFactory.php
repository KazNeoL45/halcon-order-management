<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Products::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'subtotal' => fake()->randomFloat(2, 100, 1000)
        ];
    }
}
