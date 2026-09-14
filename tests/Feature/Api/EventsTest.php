<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_index_only_returns_published_events(): void
    {
        Event::factory()->count(2)->create(['status' => 'published']);
        Event::factory()->create(['status' => 'draft']);

        $response = $this->getJson('/api/v1/events');

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }

    public function test_event_can_be_retrieved_by_slug(): void
    {
        $event = Event::factory()->create(['slug' => 'test-event']);

        $response = $this->getJson('/api/v1/events/test-event');

        $response->assertOk()->assertJsonPath('data.id', $event->id);
    }

    public function test_unpublished_event_returns_404(): void
    {
        Event::factory()->create(['slug' => 'draft-event', 'status' => 'draft']);

        $this->getJson('/api/v1/events/draft-event')->assertStatus(404);
    }
}
