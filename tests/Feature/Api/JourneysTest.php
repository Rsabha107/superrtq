<?php

namespace Tests\Feature\Api;

use App\Models\Fan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JourneysTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_journey_can_be_created_with_a_generated_itinerary(): void
    {
        $fan = Fan::factory()->verified()->create();

        $response = $this->actingAs($fan, 'sanctum')->postJson('/api/v1/me/journeys', [
            'name' => 'World Cup Trip',
            'journey_type' => 'match_trip',
            'doha_stay' => 'The Pearl',
            'arrival_at' => '2026-11-13',
            'departure_at' => '2026-11-16',
            'attached_label' => 'Qatar vs Jordan — 14 Nov 2026',
            'transport_modes' => ['Metro', 'Karwa Taxi'],
            'tours' => ['Souq Waqif Food Tour'],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.name', 'World Cup Trip');

        $itinerary = $response->json('data.itinerary');
        $this->assertCount(5, $itinerary);
        $this->assertStringContainsString('Arrival', $itinerary[0]['description']);
        $this->assertStringContainsString('Qatar vs Jordan', $itinerary[1]['description']);
        $this->assertStringContainsString('Departure', $itinerary[4]['description']);
    }

    public function test_a_minimal_journey_still_generates_an_itinerary(): void
    {
        $fan = Fan::factory()->verified()->create();

        $response = $this->actingAs($fan, 'sanctum')->postJson('/api/v1/me/journeys', [
            'name' => 'Quick Trip',
            'journey_type' => 'custom',
        ]);

        $response->assertOk();
        $this->assertSame([], $response->json('data.itinerary'));
    }

    public function test_journeys_require_a_name_and_valid_type(): void
    {
        $fan = Fan::factory()->verified()->create();

        $this->actingAs($fan, 'sanctum')->postJson('/api/v1/me/journeys', [
            'journey_type' => 'not_a_real_type',
        ])->assertStatus(422)->assertJsonValidationErrors(['name', 'journey_type']);
    }

    public function test_mine_lists_only_the_authenticated_fans_journeys(): void
    {
        $fan = Fan::factory()->verified()->create();
        $other = Fan::factory()->verified()->create();

        $this->actingAs($fan, 'sanctum')->postJson('/api/v1/me/journeys', [
            'name' => 'Mine', 'journey_type' => 'custom',
        ])->assertOk();
        $this->actingAs($other, 'sanctum')->postJson('/api/v1/me/journeys', [
            'name' => 'Not Mine', 'journey_type' => 'custom',
        ])->assertOk();

        $response = $this->actingAs($fan->fresh(), 'sanctum')->getJson('/api/v1/me/journeys');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Mine', $response->json('data.0.name'));
    }
}
