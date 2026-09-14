<?php

namespace App\Actions;

use Carbon\Carbon;

class BuildJourneyItineraryAction
{
    /**
     * A pure, deterministic template — not AI-generated, matching the
     * POC's "templated itinerary, no real booking" scope. Builds a flat
     * list of {time, description} steps from whatever the fan filled in.
     */
    public function execute(array $attributes): array
    {
        $steps = [];

        if (! empty($attributes['arrival_at'])) {
            $arrival = Carbon::parse($attributes['arrival_at']);
            $description = 'Arrival at Hamad International Airport';
            if (! empty($attributes['doha_stay'])) {
                $description .= " — check in at {$attributes['doha_stay']}";
            }
            $steps[] = [
                'time' => $arrival->format('D, j M Y'),
                'description' => $description,
            ];
        }

        if (! empty($attributes['attached_label'])) {
            $steps[] = [
                'time' => 'Match day',
                'description' => "Attend {$attributes['attached_label']}",
            ];
        }

        if (! empty($attributes['transport_modes'])) {
            $modes = implode(', ', $attributes['transport_modes']);
            $steps[] = [
                'time' => 'Getting around',
                'description' => "Recommended transport: {$modes}",
            ];
        }

        foreach ($attributes['tours'] ?? [] as $tour) {
            $steps[] = [
                'time' => 'Day trip',
                'description' => "{$tour} experience",
            ];
        }

        if (! empty($attributes['departure_at'])) {
            $departure = Carbon::parse($attributes['departure_at']);
            $steps[] = [
                'time' => $departure->format('D, j M Y'),
                'description' => 'Departure from Hamad International Airport',
            ];
        }

        return $steps;
    }
}
