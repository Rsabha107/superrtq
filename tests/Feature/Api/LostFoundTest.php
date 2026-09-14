<?php

namespace Tests\Feature\Api;

use App\Models\Fan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LostFoundTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_report_can_be_submitted_and_defaults_to_reported_status(): void
    {
        $fan = Fan::factory()->verified()->create();

        $response = $this->actingAs($fan, 'sanctum')->postJson('/api/v1/me/lost-found', [
            'item_description' => 'Blue backpack',
            'location' => 'Lusail Stadium, Gate C',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.item_description', 'Blue backpack');
        $response->assertJsonPath('data.status', 'reported');

        $this->assertDatabaseHas('lost_found_reports', [
            'fan_id' => $fan->id,
            'item_description' => 'Blue backpack',
            'status' => 'reported',
        ]);
    }

    public function test_item_description_is_required(): void
    {
        $fan = Fan::factory()->verified()->create();

        $this->actingAs($fan, 'sanctum')->postJson('/api/v1/me/lost-found', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('item_description');
    }

    public function test_mine_lists_only_the_authenticated_fans_reports(): void
    {
        $fan = Fan::factory()->verified()->create();
        $other = Fan::factory()->verified()->create();

        $this->actingAs($fan, 'sanctum')->postJson('/api/v1/me/lost-found', [
            'item_description' => 'Mine',
        ])->assertOk();
        $this->actingAs($other, 'sanctum')->postJson('/api/v1/me/lost-found', [
            'item_description' => 'Not mine',
        ])->assertOk();

        $response = $this->actingAs($fan->fresh(), 'sanctum')->getJson('/api/v1/me/lost-found');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Mine', $response->json('data.0.item_description'));
    }
}
