<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FixtureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sport' => 'football',
            'group_name' => null,
            'home_team' => $this->faker->unique()->country(),
            'away_team' => $this->faker->unique()->country(),
            'venue' => $this->faker->city().' Stadium',
            'kickoff_at' => now()->addWeek(),
            'home_score' => null,
            'away_score' => null,
            'status' => 'scheduled',
            'is_featured' => false,
        ];
    }

    public function finished(): static
    {
        return $this->state(fn () => [
            'kickoff_at' => now()->subWeek(),
            'home_score' => $this->faker->numberBetween(0, 4),
            'away_score' => $this->faker->numberBetween(0, 4),
            'status' => 'finished',
        ]);
    }
}
