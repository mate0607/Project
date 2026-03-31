<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "sale_id" => \App\Models\Sale::factory(),
            "sender_id" => \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            "receiver_id" => \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            "message" => fake()->paragraph(),
            "is_read" => fake()->boolean()
        ];
    }
}
