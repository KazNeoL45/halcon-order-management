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
        Role::create([
            'name' => 'Admin',
            'slug' => 'Administrador',
            'description' => 'Administrator role with full access',
        ]);

        Role::create([
            'name' => 'Sales',
            'slug' => 'Vendedor',
            'description' => 'Those in charge of taking orders from customers.',
        ]);

        Role::create([
            'name' => 'Purchaser',
            'slug' => 'Comprador',
            'description' => 'In case of not having any material, these are the users who manage the purchase of materials',
        ]);

        Role::create([
            'name' => 'Warehouse',
            'slug' => 'Almacenista',
            'description' => 'Who manage the warehouse and prepare the orders for routing, they also inform Purchasing about non-existent or low stock materials.
',
        ]);

        Role::create([
            'name' => 'Route',
            'slug' => 'Repartidor',
            'description' => 'Who oversee distributing orders to customers.',
        ]);
    }
}
