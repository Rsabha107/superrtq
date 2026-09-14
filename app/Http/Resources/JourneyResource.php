<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JourneyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'journey_type' => $this->journey_type,
            'from_city' => $this->from_city,
            'doha_stay' => $this->doha_stay,
            'arrival_at' => $this->arrival_at?->toDateString(),
            'departure_at' => $this->departure_at?->toDateString(),
            'attached_label' => $this->attached_label,
            'transport_modes' => $this->transport_modes ?? [],
            'tours' => $this->tours ?? [],
            'itinerary' => $this->itinerary,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
