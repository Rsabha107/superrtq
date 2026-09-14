<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journey extends Model
{
    use HasFactory;

    protected $fillable = [
        'fan_id',
        'name',
        'journey_type',
        'from_city',
        'doha_stay',
        'arrival_at',
        'departure_at',
        'attached_label',
        'transport_modes',
        'tours',
        'itinerary',
    ];

    protected $casts = [
        'arrival_at' => 'date',
        'departure_at' => 'date',
        'transport_modes' => 'array',
        'tours' => 'array',
        'itinerary' => 'array',
    ];

    public function fan(): BelongsTo
    {
        return $this->belongsTo(Fan::class);
    }
}
