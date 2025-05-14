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
            'status' => fake()->randomElement(['ordered', 'in_process', 'in_route', 'delivered', 'cancelled']),
            'invoice_number' => fake()->unique()->randomNumber(8),
            'invoice_date' => fake()->dateTime(),
            'address_id' => \App\Models\Address::factory(),
            'total' => 0
        ];
    }
    /**
     * After creating an order, generate items and recalculate total.
     */
    public function configure(): self
    {
        return $this->afterCreating(function (\App\Models\Order $order) {
            $items = \App\Models\OrderItem::factory(rand(2, 6))->create([
                'order_id' => $order->id,
            ]);
            $subtotal = $items->sum('subtotal');
            $order->update(['total' => $subtotal]);
        });
    }
}
