<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fixture extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'sport',
        'group_name',
        'home_team',
        'away_team',
        'venue',
        'kickoff_at',
        'home_score',
        'away_score',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'kickoff_at' => 'datetime',
        'home_score' => 'integer',
        'away_score' => 'integer',
        'is_featured' => 'boolean',
    ];

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'match_id');
    }
}
