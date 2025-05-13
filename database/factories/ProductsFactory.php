<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductsFactory extends Factory
{
    private static array $productNames = [
        'Laptop Pro',
        'Smartphone X',
        'Wireless Headphones',
        'Smartwatch Series 5',
        'Gaming Mouse',
        'Mechanical Keyboard',
        '4K Monitor',
        'Portable SSD 1TB',
        'Webcam HD',
        'Bluetooth Speaker',
    ];

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(self::$productNames),
            'price' => fake()->randomFloat(2, 1, 100),
            'stock' => fake()->numberBetween(1, 100),
            'description' => fake()->sentence(10),
        ];
    }
}
