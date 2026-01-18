<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'title' => fake()->sentence(3),
        'description' => fake()->sentence(10),
        'discount_percent' => fake()->numberBetween(5, 30),
        'valid_until' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
        ];
    }
}
