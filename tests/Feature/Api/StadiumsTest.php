<?php

namespace Tests\Feature\Api;

use App\Models\Stadium;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StadiumsTest extends TestCase
{
    use RefreshDatabase;

    public function test_stadiums_index_only_returns_active_stadiums(): void
    {
        Stadium::factory()->count(2)->create(['status' => 'active']);
        Stadium::factory()->create(['status' => 'inactive']);

        $response = $this->getJson('/api/v1/stadiums');

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }

    public function test_stadium_can_be_retrieved_by_slug(): void
    {
        $stadium = Stadium::factory()->create(['slug' => 'test-stadium']);

        $response = $this->getJson('/api/v1/stadiums/test-stadium');

        $response->assertOk()->assertJsonPath('data.id', $stadium->id);
    }

    public function test_inactive_stadium_returns_404(): void
    {
        Stadium::factory()->create(['slug' => 'hidden-stadium', 'status' => 'inactive']);

        $this->getJson('/api/v1/stadiums/hidden-stadium')->assertStatus(404);
    }
}
