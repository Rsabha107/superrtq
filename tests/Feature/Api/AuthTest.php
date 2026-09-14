<?php

namespace Tests\Feature\Api;

use App\Mail\FanOtpMail;
use App\Models\Fan;
use App\Models\PointsTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_a_pending_fan_and_emails_a_code(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/auth/register', [
            'email' => 'new.fan@example.qa',
        ]);

        $response->assertOk()->assertJsonStructure(['message', 'identifier']);

        $this->assertDatabaseHas('fans', [
            'email' => 'new.fan@example.qa',
            'status' => 'pending_otp',
        ]);

        $fan = Fan::where('email', 'new.fan@example.qa')->firstOrFail();
        $this->assertNotNull($fan->otp_code);
        $this->assertSame(4, strlen($fan->otp_code));
        $this->assertNotNull($fan->otp_expires_at);

        Mail::assertSent(FanOtpMail::class, fn ($mail) => $mail->hasTo($fan->email) && $mail->code === $fan->otp_code);
    }

    public function test_register_requires_an_email(): void
    {
        $this->postJson('/api/v1/auth/register', ['mobile' => '+974 5512 3456'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_login_emails_a_fresh_code_to_an_existing_fan(): void
    {
        Mail::fake();
        $fan = Fan::factory()->verified()->create(['email' => 'returning@example.qa']);

        $response = $this->postJson('/api/v1/auth/login', ['email' => 'returning@example.qa']);

        $response->assertOk()->assertJsonStructure(['message', 'identifier']);

        $fan->refresh();
        $this->assertNotNull($fan->otp_code);
        $this->assertSame(4, strlen($fan->otp_code));

        Mail::assertSent(FanOtpMail::class, fn ($mail) => $mail->hasTo($fan->email) && $mail->code === $fan->otp_code);
    }

    public function test_login_rejects_an_unknown_email(): void
    {
        $this->postJson('/api/v1/auth/login', ['email' => 'nobody@example.qa'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_login_then_verify_returns_an_already_verified_fan_without_reawarding_bonus(): void
    {
        Mail::fake();
        $fan = Fan::factory()->verified()->create(['points_balance' => 900]);

        $this->postJson('/api/v1/auth/login', ['email' => $fan->email])->assertOk();
        $fan->refresh();

        $verify = $this->postJson('/api/v1/auth/verify-otp', [
            'identifier' => $fan->email,
            'otp' => $fan->otp_code,
        ]);

        $verify->assertOk();
        $verify->assertJsonPath('fan.status', 'verified');
        $verify->assertJsonPath('fan.points_balance', 900);
    }

    public function test_otp_verification_fails_with_wrong_code(): void
    {
        Fan::factory()->withOtp('1234')->create(['email' => 'fan@example.qa', 'status' => 'pending_otp']);

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'identifier' => 'fan@example.qa',
            'otp' => '0000',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors('otp');
    }

    public function test_otp_verification_fails_once_expired(): void
    {
        $fan = Fan::factory()->withOtp('1234')->create(['status' => 'pending_otp']);
        $fan->update(['otp_expires_at' => now()->subMinute()]);

        $this->postJson('/api/v1/auth/verify-otp', [
            'identifier' => $fan->email,
            'otp' => '1234',
        ])->assertStatus(422)->assertJsonValidationErrors('otp');
    }

    public function test_otp_verification_succeeds_and_awards_registration_bonus(): void
    {
        $fan = Fan::factory()->withOtp('1234')->create(['status' => 'pending_otp', 'points_balance' => 0]);

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'identifier' => $fan->email,
            'otp' => '1234',
        ]);

        $response->assertOk()->assertJsonStructure(['token', 'fan']);
        $response->assertJsonPath('fan.points_balance', 500);

        $fan->refresh();
        $this->assertSame('pending_profile', $fan->status);
        $this->assertSame(500, $fan->points_balance);
        $this->assertNull($fan->otp_code);
        $this->assertNull($fan->otp_expires_at);

        $this->assertDatabaseHas('points_transactions', [
            'fan_id' => $fan->id,
            'points' => 500,
            'source_type' => 'registration',
        ]);
    }

    public function test_otp_verification_does_not_reaward_bonus_for_already_registered_fan(): void
    {
        $fan = Fan::factory()->withOtp('1234')->verified()->create(['points_balance' => 750]);

        $this->postJson('/api/v1/auth/verify-otp', [
            'identifier' => $fan->email,
            'otp' => '1234',
        ])->assertOk();

        $fan->refresh();
        $this->assertSame(750, $fan->points_balance);
    }

    public function test_update_details_saves_step_two_fields_without_awarding_points(): void
    {
        $fan = Fan::factory()->create(['status' => 'pending_profile', 'points_balance' => 500, 'display_name' => null]);

        $response = $this->actingAs($fan, 'sanctum')->putJson('/api/v1/me/details', [
            'first_name' => 'Ahmed',
            'last_name' => 'Abdulla',
            'birth_date' => '1998-01-17',
            'nationality' => 'Qatar',
            'gender' => 'male',
            'country_of_residence' => 'Qatar',
            'language' => 'en',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.first_name', 'Ahmed');
        $response->assertJsonPath('data.status', 'pending_profile');
        $response->assertJsonPath('data.points_balance', 500);

        $fan->refresh();
        $this->assertSame('Ahmed Abdulla', $fan->display_name);
        $this->assertSame('Qatar', $fan->nationality);
    }

    public function test_profile_update_issues_fan_id_and_profile_bonus(): void
    {
        $fan = Fan::factory()->create(['status' => 'pending_profile', 'points_balance' => 500]);

        $response = $this->actingAs($fan, 'sanctum')->putJson('/api/v1/me/profile', [
            'display_name' => 'Test Fan',
            'language' => 'en',
            'sports' => ['Football'],
            'interested_events' => [],
            'interests' => [],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.status', 'verified');
        $response->assertJsonPath('data.points_balance', 750);
        $response->assertJsonPath('data.display_name', 'Test Fan');

        $fan->refresh();
        $this->assertNotNull($fan->fan_number);
        $this->assertNotNull($fan->fan_id);
        $this->assertSame(750, $fan->points_balance);

        $this->assertDatabaseHas('points_transactions', [
            'fan_id' => $fan->id,
            'points' => 250,
            'source_type' => 'fan_id_issuance',
        ]);
    }

    public function test_full_registration_journey_awards_750_points(): void
    {
        Mail::fake();

        $this->postJson('/api/v1/auth/register', ['email' => 'journey@example.qa'])->assertOk();
        $fan = Fan::where('email', 'journey@example.qa')->firstOrFail();

        $verify = $this->postJson('/api/v1/auth/verify-otp', [
            'identifier' => $fan->email,
            'otp' => $fan->otp_code,
        ])->assertOk();

        $token = $verify->json('token');

        $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson('/api/v1/me/details', ['first_name' => 'Test', 'last_name' => 'Fan'])
            ->assertOk();

        $profile = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson('/api/v1/me/profile', ['display_name' => 'Test Fan'])
            ->assertOk();

        $profile->assertJsonPath('data.points_balance', 750);

        $sum = (int) PointsTransaction::where('fan_id', $fan->id)->sum('points');
        $this->assertSame(750, $sum);
    }

    public function test_editing_profile_after_verification_awards_personalization_bonus_once(): void
    {
        $fan = Fan::factory()->verified()->create(['points_balance' => 750]);

        $first = $this->actingAs($fan, 'sanctum')->putJson('/api/v1/me/profile', [
            'display_name' => 'Updated Name',
            'sports' => ['Basketball'],
        ]);

        $first->assertOk();
        $first->assertJsonPath('data.points_balance', 900);

        $this->assertDatabaseHas('points_transactions', [
            'fan_id' => $fan->id,
            'points' => 150,
            'source_type' => 'personalization_bonus',
        ]);

        $second = $this->actingAs($fan->fresh(), 'sanctum')->putJson('/api/v1/me/profile', [
            'display_name' => 'Updated Name Again',
        ]);

        $second->assertOk();
        $second->assertJsonPath('data.points_balance', 900);

        $this->assertSame(
            1,
            PointsTransaction::where('fan_id', $fan->id)
                ->where('source_type', 'personalization_bonus')
                ->count(),
        );
    }

    public function test_logout_revokes_the_current_token(): void
    {
        $fan = Fan::factory()->verified()->create();
        $newToken = $fan->createToken('mobile');
        $tokenId = $newToken->accessToken->id;

        $response = $this->withHeader('Authorization', "Bearer {$newToken->plainTextToken}")
            ->postJson('/api/v1/auth/logout');

        $response->assertOk();

        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);
    }
}
