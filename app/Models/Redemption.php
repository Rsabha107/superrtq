<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Redemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'fan_id',
        'reward_id',
        'points_cost',
        'status',
        'redeemed_at',
    ];

    protected $casts = [
        'points_cost' => 'integer',
        'redeemed_at' => 'datetime',
    ];

    public function fan(): BelongsTo
    {
        return $this->belongsTo(Fan::class);
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }
}
