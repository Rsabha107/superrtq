<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'event_type' => $this->event_type,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'venue' => $this->venue,
            'start_at' => $this->start_at?->toIso8601String(),
            'end_at' => $this->end_at?->toIso8601String(),
            'image' => $this->image,
            'accent' => $this->accent,
            'is_featured' => $this->is_featured,
        ];
    }
}
