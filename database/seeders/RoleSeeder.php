<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'slug' => 'admin',
            'description' => 'Administrator role with full access',
        ]);

        Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Regular user role with limited access',
        ]);
    }
}
