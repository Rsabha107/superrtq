<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StadiumFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->city().' Stadium';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1, 100000),
            'city' => $this->faker->city(),
            'capacity' => $this->faker->numberBetween(20000, 90000),
            'description' => $this->faker->sentence(),
            'status' => 'active',
        ];
    }
}
