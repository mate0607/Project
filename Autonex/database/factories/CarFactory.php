<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'make_model' => fake()->randomElement([
                'BMW 320d',
                'BMW 520d',
                'Audi A4',
                'Audi A6',
                'Mercedes C200',
                'Mercedes E220d',
                'Volkswagen Golf',
                'Volkswagen Passat',
                'Toyota Corolla',
                'Toyota RAV4',
                'Skoda Octavia',
                'Skoda Superb',
                'Ford Focus',
                'Hyundai Tucson',
                'Kia Sportage',
                'Opel Astra',
                'Renault Mégane',
                'Suzuki Vitara',
                'Mazda CX-5',
                'Peugeot 3008',
            ]),
            'vin' => strtoupper(fake()->bothify('??#??##??########')),
            'license_plate' => strtoupper(fake()->regexify('[A-Z]{3}-[0-9]{3}')),
            'year' => fake()->numberBetween(2010, 2025),
        ];
    }
}
