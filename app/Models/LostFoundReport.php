<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostFoundReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'fan_id',
        'item_description',
        'location',
        'date_lost',
        'status',
    ];

    protected $casts = [
        'date_lost' => 'date',
    ];

    public function fan(): BelongsTo
    {
        return $this->belongsTo(Fan::class);
    }
}
