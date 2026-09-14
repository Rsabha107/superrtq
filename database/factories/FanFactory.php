<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'display_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => null,
            'fan_number' => null,
            'fan_id' => null,
            'status' => 'pending_otp',
            'language' => 'en',
            'member_since' => null,
            'points_balance' => 0,
            'preferences' => null,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn () => [
            'status' => 'verified',
            'fan_number' => (string) $this->faker->unique()->numberBetween(1000, 9999),
            'fan_id' => 'QA-'.$this->faker->unique()->numberBetween(1000, 9999).'-'.$this->faker->numberBetween(1000, 9999),
            'member_since' => now(),
        ]);
    }

    public function withOtp(string $code = '1234'): static
    {
        return $this->state(fn () => [
            'otp_code' => $code,
            'otp_expires_at' => now()->addMinutes(10),
        ]);
    }
}
