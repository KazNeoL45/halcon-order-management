<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Mexico', 'code' => 'MX'],
            ['name' => 'United States', 'code' => 'US'],
            ['name' => 'Canada', 'code' => 'CA'],
        ];
        foreach ($countries as $data) {
            Country::firstOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name'], 'code' => $data['code']]
            );
        }
    }
}