<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        "user_id" => fake()->numberBetween(1, 10),
        "make_model" => fake()->randomElement([
            "BMW 320d",
            "Audi A4",
            "Mercedes C200",
            "Volkswagen Golf",
            "Toyota Corolla"
        ]),

        "vin" => fake()->bothify('??###########'),

        "year" => fake()->numberBetween(2000, 2024)
        ];
    }
}
