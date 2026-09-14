<?php

namespace Tests\Feature\Api;

use App\Models\Fan;
use App\Models\PointsTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_points_balance_requires_authentication(): void
    {
        $this->getJson('/api/v1/fan/points')->assertStatus(401);
    }

    public function test_points_balance_is_returned_for_authenticated_fan(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 4820]);

        $response = $this->actingAs($fan, 'sanctum')->getJson('/api/v1/fan/points');

        $response->assertOk()->assertJson(['points_balance' => 4820]);
    }

    public function test_points_transactions_are_listed_most_recent_first(): void
    {
        $fan = Fan::factory()->verified()->create();

        PointsTransaction::create([
            'fan_id' => $fan->id,
            'type' => 'earn',
            'points' => 100,
            'description' => 'Older',
            'created_at' => now()->subDay(),
        ]);

        PointsTransaction::create([
            'fan_id' => $fan->id,
            'type' => 'earn',
            'points' => 50,
            'description' => 'Newer',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($fan, 'sanctum')->getJson('/api/v1/fan/points/transactions');

        $response->assertOk();
        $this->assertSame('Newer', $response->json('data.0.description'));
        $this->assertSame('Older', $response->json('data.1.description'));
    }
}
