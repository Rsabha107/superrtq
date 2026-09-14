<?php

namespace Tests\Feature\Api;

use App\Models\Fan;
use App\Models\PointsTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameSessionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_recording_a_flag_quiz_session_awards_points(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 0]);

        $response = $this->actingAs($fan, 'sanctum')->postJson('/api/v1/games/sessions', [
            'game' => 'flag_quiz',
            'score' => 8,
        ]);

        $response->assertOk();
        $response->assertJsonPath('session.score', 8);
        $response->assertJsonPath('session.points_awarded', 40);
        $response->assertJsonPath('fan.points_balance', 40);

        $this->assertDatabaseHas('points_transactions', [
            'fan_id' => $fan->id,
            'points' => 40,
            'source_type' => 'mini_game',
        ]);
    }

    public function test_score_is_clamped_to_the_games_maximum(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 0]);

        $response = $this->actingAs($fan, 'sanctum')->postJson('/api/v1/games/sessions', [
            'game' => 'penalty_shootout',
            'score' => 999,
        ]);

        $response->assertOk();
        // penalty_shootout max score 5, 10 pts each = 50, not 9990.
        $response->assertJsonPath('session.score', 5);
        $response->assertJsonPath('session.points_awarded', 50);
    }

    public function test_unknown_game_is_rejected(): void
    {
        $fan = Fan::factory()->verified()->create();

        $this->actingAs($fan, 'sanctum')->postJson('/api/v1/games/sessions', [
            'game' => 'not_a_real_game',
            'score' => 5,
        ])->assertStatus(422)->assertJsonValidationErrors('game');
    }

    public function test_mini_games_are_replayable_for_points(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 0]);

        $this->actingAs($fan, 'sanctum')->postJson('/api/v1/games/sessions', [
            'game' => 'flappy_dunk', 'score' => 3,
        ])->assertOk();
        $this->actingAs($fan->fresh(), 'sanctum')->postJson('/api/v1/games/sessions', [
            'game' => 'flappy_dunk', 'score' => 5,
        ])->assertOk();

        $this->assertSame(2, PointsTransaction::where('fan_id', $fan->id)->count());
        $this->assertSame(80, $fan->fresh()->points_balance);
    }
}
