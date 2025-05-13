<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\Country;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mexico states
        $mexico = Country::where('code', 'MX')->first();
        if ($mexico) {
            $states = [
                'Aguascalientes', 'Baja California', 'Baja California Sur',
                'Campeche', 'Chiapas', 'Chihuahua', 'Ciudad de México',
                'Coahuila', 'Colima', 'Durango', 'Guanajuato', 'Guerrero',
                'Hidalgo', 'Jalisco', 'Estado de México', 'Michoacán',
                'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla',
                'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa',
                'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz',
                'Yucatán', 'Zacatecas'
            ];
            foreach ($states as $name) {
                State::firstOrCreate(
                    ['country_id' => $mexico->id, 'name' => $name],
                    ['name' => $name]
                );
            }
        }
        // United States states (partial list)
        $usa = Country::where('code', 'US')->first();
        if ($usa) {
            $usStates = ['California', 'New York', 'Texas', 'Florida', 'Illinois'];
            foreach ($usStates as $name) {
                State::firstOrCreate(
                    ['country_id' => $usa->id, 'name' => $name],
                    ['name' => $name]
                );
            }
        }
        // Canada provinces (partial list)
        $canada = Country::where('code', 'CA')->first();
        if ($canada) {
            $caProvinces = ['Alberta', 'British Columbia', 'Ontario', 'Quebec'];
            foreach ($caProvinces as $name) {
                State::firstOrCreate(
                    ['country_id' => $canada->id, 'name' => $name],
                    ['name' => $name]
                );
            }
        }
    }
}