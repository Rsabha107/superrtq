<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PredictionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'match_id' => $this->match_id,
            'predicted_home_score' => $this->predicted_home_score,
            'predicted_away_score' => $this->predicted_away_score,
            'points_awarded' => $this->points_awarded,
            'created_at' => $this->created_at?->toIso8601String(),
            'fixture' => new FixtureResource($this->whenLoaded('fixture')),
        ];
    }
}
