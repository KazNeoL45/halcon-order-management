<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderItem::factory(10)->create()
        ->each(function ($orderItem) {
            $order = $orderItem->order;
            $total = $order->items()->sum('subtotal');
            $order->update(['total' => $total]);
        });
    }
}
