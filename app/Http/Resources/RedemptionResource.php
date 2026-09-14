<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RedemptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reward' => new RewardResource($this->whenLoaded('reward')),
            'points_cost' => $this->points_cost,
            'status' => $this->status,
            'redeemed_at' => $this->redeemed_at?->toIso8601String(),
        ];
    }
}
