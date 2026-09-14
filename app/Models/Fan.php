<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Fan extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'display_name',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'fan_number',
        'fan_id',
        'status',
        'language',
        'birth_date',
        'nationality',
        'gender',
        'country_of_residence',
        'member_since',
        'points_balance',
        'preferences',
        'otp_code',
        'otp_expires_at',
    ];

    protected $hidden = [
        'otp_code',
        'otp_expires_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'member_since' => 'date',
        'points_balance' => 'integer',
        'preferences' => 'array',
        'otp_expires_at' => 'datetime',
    ];

    public function pointsTransactions(): HasMany
    {
        return $this->hasMany(PointsTransaction::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class);
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function gameSessions(): HasMany
    {
        return $this->hasMany(GameSession::class);
    }

    public function journeys(): HasMany
    {
        return $this->hasMany(Journey::class);
    }

    public function lostFoundReports(): HasMany
    {
        return $this->hasMany(LostFoundReport::class);
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    /**
     * Generate a unique Fan Number / Fan ID pair.
     * Numbering policy is TBD for production (DECISIONS.md); the POC assigns
     * a random 4-digit fan number with a matching QA-prefixed Fan ID.
     */
    public static function generateIdentity(): array
    {
        do {
            $fanNumber = (string) random_int(1000, 9999);
        } while (self::where('fan_number', $fanNumber)->exists());

        $fanId = sprintf('QA-%s-%04d', $fanNumber, random_int(0, 9999));

        return [$fanNumber, $fanId];
    }

    /**
     * Generate a 4-digit OTP code, matching the approved onboarding mockup.
     */
    public static function generateOtp(): string
    {
        return str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
