<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => 'Teljes átvizsgálás',
            'description' => 'Állapotfelmérés, hibakód olvasás, alap ellenőrzések.',
            'price' => 24990,
            'duration_minutes' => 60,
        ]);

        Service::factory(10)->create();
    }
}
