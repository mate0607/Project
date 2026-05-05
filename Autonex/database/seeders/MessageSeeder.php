<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Szandekosan nem seedelunk dummy uzeneteket, hogy az admin uzenetlista
        // csak valos beszelgeteseket mutasson.
    }
}
