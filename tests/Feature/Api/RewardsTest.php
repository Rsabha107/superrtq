<?php

namespace Tests\Feature\Api;

use App\Models\Fan;
use App\Models\PointsTransaction;
use App\Models\Reward;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RewardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_rewards_index_only_returns_active_rewards(): void
    {
        Reward::factory()->count(2)->create(['status' => 'active']);
        Reward::factory()->create(['status' => 'inactive']);

        $response = $this->getJson('/api/v1/rewards');

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }

    public function test_redeeming_a_reward_requires_authentication(): void
    {
        $reward = Reward::factory()->create();

        $this->postJson("/api/v1/rewards/{$reward->id}/redeem")->assertStatus(401);
    }

    public function test_redemption_fails_when_fan_has_insufficient_points(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 100]);
        $reward = Reward::factory()->create(['points_cost' => 500, 'quantity' => 10]);

        $response = $this->actingAs($fan, 'sanctum')
            ->postJson("/api/v1/rewards/{$reward->id}/redeem");

        $response->assertStatus(422)->assertJsonValidationErrors('points');

        $fan->refresh();
        $this->assertSame(100, $fan->points_balance);
        $this->assertDatabaseCount('redemptions', 0);
    }

    public function test_successful_redemption_deducts_points_and_records_transaction(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 1000]);
        PointsTransaction::create([
            'fan_id' => $fan->id,
            'type' => 'earn',
            'points' => 1000,
            'description' => 'Starting balance',
        ]);
        $reward = Reward::factory()->create(['points_cost' => 400, 'quantity' => 5]);

        $response = $this->actingAs($fan, 'sanctum')
            ->postJson("/api/v1/rewards/{$reward->id}/redeem");

        $response->assertOk();
        $response->assertJsonPath('fan.points_balance', 600);

        $fan->refresh();
        $reward->refresh();

        $this->assertSame(600, $fan->points_balance);
        $this->assertSame(4, $reward->quantity);

        $this->assertDatabaseHas('redemptions', [
            'fan_id' => $fan->id,
            'reward_id' => $reward->id,
            'points_cost' => 400,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('points_transactions', [
            'fan_id' => $fan->id,
            'type' => 'redeem',
            'points' => -400,
        ]);

        // Balance must always equal the sum of the fan's points transactions.
        $sum = (int) PointsTransaction::where('fan_id', $fan->id)->sum('points');
        $this->assertSame($fan->points_balance, $sum);
    }

    public function test_redemption_is_blocked_once_stock_is_exhausted(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 10000]);
        $reward = Reward::factory()->create(['points_cost' => 100, 'quantity' => 1]);

        $this->actingAs($fan, 'sanctum')->postJson("/api/v1/rewards/{$reward->id}/redeem")->assertOk();

        $response = $this->actingAs($fan, 'sanctum')->postJson("/api/v1/rewards/{$reward->id}/redeem");

        $response->assertStatus(422)->assertJsonValidationErrors('reward');
    }
}
