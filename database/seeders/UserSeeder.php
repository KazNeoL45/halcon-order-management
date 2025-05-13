<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => 'admin1234',
                'role_id' => 1,
            ],
            [
                'name' => 'Sales',
                'email' => 'sales@gmail.com',
                'password' => 'sales1234',
                'role_id' => 2,
            ],
            [
                'name' => 'Purchaser',
                'email' => 'purchaser@gmail.com',
                'password' => 'purchaser1234',
                'role_id' => 3,
            ],
            [
                'name' => 'Warehouse',
                'email' => 'warehouse@gmail.com',
                'password' => 'warehouse1234',
                'role_id' => 4,
            ],
            [
                'name' => 'Route',
                'email' => 'route@gmail.com',
                'password' => 'route1234',
                'role_id' => 5,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
