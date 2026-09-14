<?php

namespace Tests\Feature\Api;

use App\Models\Fan;
use App\Models\Fixture;
use App\Models\PointsTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_submitting_a_prediction_awards_points_once(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 900]);
        $fixture = Fixture::factory()->create(['kickoff_at' => now()->addDay()]);

        $response = $this->actingAs($fan, 'sanctum')->postJson("/api/v1/matches/{$fixture->id}/predict", [
            'predicted_home_score' => 2,
            'predicted_away_score' => 1,
        ]);

        $response->assertOk();
        $response->assertJsonPath('fan.points_balance', 920);
        $response->assertJsonPath('prediction.points_awarded', 20);

        $this->assertDatabaseHas('points_transactions', [
            'fan_id' => $fan->id,
            'points' => 20,
            'source_type' => 'match_prediction',
        ]);
    }

    public function test_a_fixture_cannot_be_predicted_twice_by_the_same_fan(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 0]);
        $fixture = Fixture::factory()->create(['kickoff_at' => now()->addDay()]);

        $this->actingAs($fan, 'sanctum')->postJson("/api/v1/matches/{$fixture->id}/predict", [
            'predicted_home_score' => 1,
            'predicted_away_score' => 0,
        ])->assertOk();

        $this->actingAs($fan->fresh(), 'sanctum')->postJson("/api/v1/matches/{$fixture->id}/predict", [
            'predicted_home_score' => 2,
            'predicted_away_score' => 0,
        ])->assertStatus(422)->assertJsonValidationErrors('match');

        $this->assertSame(1, PointsTransaction::where('fan_id', $fan->id)->count());
        $this->assertSame(20, $fan->fresh()->points_balance);
    }

    public function test_a_fixture_cannot_be_predicted_after_kickoff(): void
    {
        $fan = Fan::factory()->verified()->create();
        $fixture = Fixture::factory()->finished()->create();

        $this->actingAs($fan, 'sanctum')->postJson("/api/v1/matches/{$fixture->id}/predict", [
            'predicted_home_score' => 1,
            'predicted_away_score' => 0,
        ])->assertStatus(422)->assertJsonValidationErrors('match');
    }

    public function test_predictions_require_a_score_for_both_teams(): void
    {
        $fan = Fan::factory()->verified()->create();
        $fixture = Fixture::factory()->create(['kickoff_at' => now()->addDay()]);

        $this->actingAs($fan, 'sanctum')->postJson("/api/v1/matches/{$fixture->id}/predict", [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['predicted_home_score', 'predicted_away_score']);
    }

    public function test_mine_lists_only_the_authenticated_fans_predictions(): void
    {
        $fan = Fan::factory()->verified()->create();
        $other = Fan::factory()->verified()->create();
        $fixture = Fixture::factory()->create(['kickoff_at' => now()->addDay()]);
        $otherFixture = Fixture::factory()->create(['kickoff_at' => now()->addDay()]);

        $this->actingAs($fan, 'sanctum')->postJson("/api/v1/matches/{$fixture->id}/predict", [
            'predicted_home_score' => 1, 'predicted_away_score' => 1,
        ])->assertOk();
        $this->actingAs($other, 'sanctum')->postJson("/api/v1/matches/{$otherFixture->id}/predict", [
            'predicted_home_score' => 0, 'predicted_away_score' => 0,
        ])->assertOk();

        $response = $this->actingAs($fan->fresh(), 'sanctum')->getJson('/api/v1/me/predictions');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame($fixture->id, $response->json('data.0.match_id'));
    }
}
