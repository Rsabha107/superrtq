<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RewardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->sentence(),
            'image' => null,
            'points_cost' => $this->faker->numberBetween(100, 2000),
            'quantity' => 10,
            'status' => 'active',
            'start_at' => null,
            'end_at' => null,
        ];
    }
}
