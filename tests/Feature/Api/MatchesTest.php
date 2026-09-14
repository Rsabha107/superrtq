<?php

namespace Tests\Feature\Api;

use App\Models\Fixture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_matches_index_lists_all_fixtures_ordered_by_kickoff(): void
    {
        Fixture::factory()->create(['home_team' => 'Later', 'kickoff_at' => now()->addDays(5)]);
        Fixture::factory()->create(['home_team' => 'Sooner', 'kickoff_at' => now()->addDay()]);

        $response = $this->getJson('/api/v1/matches');

        $response->assertOk();
        $this->assertSame('Sooner', $response->json('data.0.home_team'));
        $this->assertSame('Later', $response->json('data.1.home_team'));
    }

    public function test_matches_index_filters_by_sport(): void
    {
        Fixture::factory()->create(['sport' => 'football']);
        Fixture::factory()->create(['sport' => 'basketball']);

        $response = $this->getJson('/api/v1/matches?sport=basketball');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('basketball', $response->json('data.0.sport'));
    }

    public function test_match_can_be_shown(): void
    {
        $fixture = Fixture::factory()->create();

        $this->getJson("/api/v1/matches/{$fixture->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $fixture->id);
    }

    public function test_groups_computes_standings_from_finished_fixtures_only(): void
    {
        Fixture::factory()->finished()->create([
            'group_name' => 'Group A', 'home_team' => 'Qatar', 'away_team' => 'Jordan',
            'home_score' => 2, 'away_score' => 1,
        ]);
        Fixture::factory()->finished()->create([
            'group_name' => 'Group A', 'home_team' => 'Jordan', 'away_team' => 'Qatar',
            'home_score' => 1, 'away_score' => 1,
        ]);
        // Scheduled fixture in the same group must not affect standings.
        Fixture::factory()->create([
            'group_name' => 'Group A', 'home_team' => 'Qatar', 'away_team' => 'Iraq',
        ]);

        $response = $this->getJson('/api/v1/matches/groups');

        $response->assertOk();
        $groupA = collect($response->json('data'))->firstWhere('group_name', 'Group A');
        $qatar = collect($groupA['standings'])->firstWhere('team', 'Qatar');

        $this->assertSame(2, $qatar['played']);
        $this->assertSame(1, $qatar['won']);
        $this->assertSame(1, $qatar['drawn']);
        $this->assertSame(0, $qatar['lost']);
        $this->assertSame(3, $qatar['goals_for']);
        $this->assertSame(2, $qatar['goals_against']);
        $this->assertSame(1, $qatar['goal_difference']);
        $this->assertSame(4, $qatar['points']);
    }
}
