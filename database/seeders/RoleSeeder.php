<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => 'Admin'],
            ['slug' => 'Administrador', 'description' => 'Administrator role with full access']
        );

        Role::firstOrCreate(
            ['name' => 'Sales'],
            ['slug' => 'Vendedor', 'description' => 'Those in charge of taking orders from customers.']
        );

        Role::firstOrCreate(
            ['name' => 'Purchaser'],
            ['slug' => 'Comprador', 'description' => 'In case of not having any material, these are the users who manage the purchase of materials']
        );

        Role::firstOrCreate(
            ['name' => 'Warehouse'],
            ['slug' => 'Almacenista', 'description' => 'Who manage the warehouse and prepare the orders for routing, they also inform Purchasing about non-existent or low stock materials.']
        );

        Role::firstOrCreate(
            ['name' => 'Route'],
            ['slug' => 'Repartidor', 'description' => 'Who oversee distributing orders to customers.']
        );
    }
}
