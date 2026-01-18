<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::create([
            'title' => 'Téli szerviz akció',
            'description' => '15% kedvezmény teljes átvizsgálásra',
            'discount_percent' => 15,
            'valid_until' => '2026-02-28',
        ]);

        Promotion::factory(5)->create();
    }
}
