<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointsRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points',
        'event_id',
        'status',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
