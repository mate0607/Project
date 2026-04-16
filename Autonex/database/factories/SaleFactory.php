<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        "car_id" => \App\Models\Car::factory(),
        "buyer_id" => \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory(),
        "seller_id" => \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory(),
        "price" => fake()->numberBetween(500000, 5000000),
        "description" => fake()->paragraph(),
        "car_condition" => fake()->randomElement([
            "Excellent",
            "Good",
            "Fair",
            "Poor"
        ]),
        "mileage" => fake()->numberBetween(0, 200000),
        "is_active" => fake()->boolean()
        ];
    }
}
