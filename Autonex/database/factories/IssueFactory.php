<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'car_id' => \App\Models\Car::factory(),
            'category' => fake()->randomElement([
                'Engine',
                'Brakes',
                'Transmission',
                'Electrical',
                'Suspension',
            ]),
            'description' => fake()->paragraph(),
            'urgency' => fake()->randomElement(['low', 'medium', 'high']),
        ];
    }
}
