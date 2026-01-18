<?php

namespace Database\Seeders;

use App\Models\Garage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GarageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Garage::create([
            'name' => 'Autonex Prémium Szerviz',
            'address' => '1111 Budapest, Minta utca 1.',
            'phone' => '+36 30 123 4567',
            'email' => 'szerviz@autonex.hu',
            'description' => 'Gyors diagnosztika, megbízható javítás, korrekt árak.',
        ]);

        Garage::factory(10)->create();
    }
}
