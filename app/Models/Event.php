<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'event_type',
        'short_description',
        'description',
        'venue',
        'start_at',
        'end_at',
        'image',
        'accent',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function pointsRules(): HasMany
    {
        return $this->hasMany(PointsRule::class);
    }
}
