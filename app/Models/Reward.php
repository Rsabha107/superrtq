<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'points_cost',
        'quantity',
        'status',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'points_cost' => 'integer',
        'quantity' => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class);
    }

    public function isAvailable(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->quantity !== null && $this->quantity <= 0) {
            return false;
        }

        return true;
    }
}
