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
        "buyer_id" => fake()->numberBetween(1, 10),
        "seller_id" => fake()->numberBetween(1, 10),
        "price" => fake()->numberBetween(5000, 50000),
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
