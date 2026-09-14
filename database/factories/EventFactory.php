<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(1, 100000),
            'event_type' => $this->faker->randomElement(['football', 'basketball', 'tennis', 'festival', 'esports']),
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'venue' => $this->faker->city(),
            'start_at' => now()->addDays($this->faker->numberBetween(1, 60)),
            'end_at' => now()->addDays($this->faker->numberBetween(61, 62)),
            'image' => null,
            'accent' => '#8A1538',
            'status' => 'published',
            'is_featured' => false,
        ];
    }
}
