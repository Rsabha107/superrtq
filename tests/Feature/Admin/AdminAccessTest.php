<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\Fan;
use App\Models\Fixture;
use App\Models\GameSession;
use App\Models\LostFoundReport;
use App\Models\Prediction;
use App\Models\Stadium;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin/events')->assertRedirect('/login');
    }

    public function test_admin_can_view_dashboard(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/dashboard')->assertOk();
    }

    public function test_admin_can_manage_events(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/events', [
            'title' => 'Test Event',
            'event_type' => 'football',
            'start_at' => now()->addWeek()->toDateTimeString(),
            'status' => 'published',
        ])->assertRedirect('/admin/events');

        $this->assertDatabaseHas('events', ['title' => 'Test Event']);

        $event = Event::where('title', 'Test Event')->firstOrFail();

        $this->actingAs($admin)->put("/admin/events/{$event->id}", [
            'title' => 'Updated Event',
            'event_type' => 'football',
            'start_at' => now()->addWeek()->toDateTimeString(),
            'status' => 'draft',
        ])->assertRedirect('/admin/events');

        $this->assertDatabaseHas('events', ['id' => $event->id, 'title' => 'Updated Event']);

        $this->actingAs($admin)->delete("/admin/events/{$event->id}")
            ->assertRedirect('/admin/events');

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_admin_can_manage_stadiums(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/stadiums', [
            'name' => 'Test Stadium',
            'city' => 'Doha',
            'capacity' => 50000,
            'status' => 'active',
        ])->assertRedirect('/admin/stadiums');

        $this->assertDatabaseHas('stadiums', ['name' => 'Test Stadium']);

        $stadium = Stadium::where('name', 'Test Stadium')->firstOrFail();

        $this->actingAs($admin)->put("/admin/stadiums/{$stadium->id}", [
            'name' => 'Updated Stadium',
            'city' => 'Doha',
            'capacity' => 55000,
            'status' => 'active',
        ])->assertRedirect('/admin/stadiums');

        $this->assertDatabaseHas('stadiums', ['id' => $stadium->id, 'name' => 'Updated Stadium']);

        $this->actingAs($admin)->delete("/admin/stadiums/{$stadium->id}")
            ->assertRedirect('/admin/stadiums');

        $this->assertDatabaseMissing('stadiums', ['id' => $stadium->id]);
    }

    public function test_admin_can_manage_matches(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/matches', [
            'sport' => 'football',
            'home_team' => 'Test Home',
            'away_team' => 'Test Away',
            'kickoff_at' => now()->addWeek()->toDateTimeString(),
            'status' => 'scheduled',
        ])->assertRedirect('/admin/matches');

        $this->assertDatabaseHas('matches', ['home_team' => 'Test Home']);

        $fixture = Fixture::where('home_team', 'Test Home')->firstOrFail();

        $this->actingAs($admin)->put("/admin/matches/{$fixture->id}", [
            'sport' => 'football',
            'home_team' => 'Test Home',
            'away_team' => 'Test Away',
            'kickoff_at' => now()->addWeek()->toDateTimeString(),
            'home_score' => 2,
            'away_score' => 1,
            'status' => 'finished',
        ])->assertRedirect('/admin/matches');

        $this->assertDatabaseHas('matches', ['id' => $fixture->id, 'status' => 'finished']);

        $this->actingAs($admin)->delete("/admin/matches/{$fixture->id}")
            ->assertRedirect('/admin/matches');

        $this->assertDatabaseMissing('matches', ['id' => $fixture->id]);
    }

    public function test_admin_can_view_predictions_list(): void
    {
        $admin = User::factory()->create();
        $fan = Fan::factory()->verified()->create();
        $fixture = Fixture::factory()->create();
        Prediction::create([
            'fan_id' => $fan->id,
            'match_id' => $fixture->id,
            'predicted_home_score' => 1,
            'predicted_away_score' => 0,
            'points_awarded' => 20,
        ]);

        $this->actingAs($admin)->get('/admin/predictions')->assertOk();
    }

    public function test_admin_can_view_game_sessions_list(): void
    {
        $admin = User::factory()->create();
        $fan = Fan::factory()->verified()->create();
        GameSession::create([
            'fan_id' => $fan->id,
            'game' => 'flag_quiz',
            'score' => 8,
            'points_awarded' => 40,
        ]);

        $this->actingAs($admin)->get('/admin/game-sessions')->assertOk();
    }

    public function test_admin_can_view_and_update_lost_found_reports(): void
    {
        $admin = User::factory()->create();
        $fan = Fan::factory()->verified()->create();
        $report = LostFoundReport::create([
            'fan_id' => $fan->id,
            'item_description' => 'Blue backpack',
            'status' => 'reported',
        ]);

        $this->actingAs($admin)->get('/admin/lost-found')->assertOk();

        $this->actingAs($admin)->put("/admin/lost-found/{$report->id}", [
            'status' => 'found',
        ])->assertRedirect('/admin/lost-found');

        $this->assertDatabaseHas('lost_found_reports', [
            'id' => $report->id,
            'status' => 'found',
        ]);
    }

    public function test_admin_can_view_fan_list_and_detail(): void
    {
        $admin = User::factory()->create();
        $fan = Fan::factory()->verified()->create(['display_name' => 'Jane Fan']);

        $this->actingAs($admin)->get('/admin/fans')->assertOk();
        $this->actingAs($admin)->get("/admin/fans/{$fan->id}")->assertOk();
    }
}
