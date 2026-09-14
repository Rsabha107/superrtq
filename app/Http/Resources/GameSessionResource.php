<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'game' => $this->game,
            'score' => $this->score,
            'points_awarded' => $this->points_awarded,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
