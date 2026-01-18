<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
            'Olajcsere',
            'Fékellenőrzés',
            'Futómű beállítás',
            'Klímatöltés',
            'Általános átvizsgálás',
            'Gumicsere',
        ]),
        'description' => fake()->sentence(10),
        'price' => fake()->numberBetween(8000, 60000),
        'duration_minutes' => fake()->numberBetween(15, 180),
        ];
    }
}
