<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'car_id' => \App\Models\Car::factory(),
            'sender_id' => \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            'receiver_id' => \App\Models\User::inRandomOrder()->value('id') ?? \App\Models\User::factory(),
            'message' => fake()->paragraph(),
            'is_read' => fake()->boolean(),
        ];
    }
}
