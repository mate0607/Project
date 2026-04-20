<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'car_id' => Car::factory(),
            'date' => fake()->dateTimeBetween('+1 days', '+1 month')->format('Y-m-d'),
            'time' => fake()->time('H:i:s'),
            'service' => fake()->randomElement([
                'Olajcsere', 'Fékbetét csere', 'Szerviz átvizsgálás',
                'Gumicsere', 'Futómű beállítás', 'Klíma töltés',
                'Akkumulátor csere', 'Vezérlés csere', 'Féktárcsa csere',
            ]),
            'description' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'in_progress', 'completed', 'cancelled']),
        ];
    }
}
