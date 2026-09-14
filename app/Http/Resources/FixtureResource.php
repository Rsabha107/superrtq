<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FixtureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sport' => $this->sport,
            'group_name' => $this->group_name,
            'home_team' => $this->home_team,
            'away_team' => $this->away_team,
            'venue' => $this->venue,
            'kickoff_at' => $this->kickoff_at?->toIso8601String(),
            'home_score' => $this->home_score,
            'away_score' => $this->away_score,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
        ];
    }
}
