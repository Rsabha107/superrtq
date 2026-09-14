<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\BuildJourneyItineraryAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\JourneyResource;
use App\Models\Journey;
use Illuminate\Http\Request;

class JourneyController extends Controller
{
    public function index(Request $request)
    {
        $journeys = $request->user()->journeys()->latest()->get();

        return JourneyResource::collection($journeys);
    }

    public function store(Request $request, BuildJourneyItineraryAction $action)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'journey_type' => ['required', 'string', 'in:match_trip,tour_only,custom'],
            'from_city' => ['nullable', 'string', 'max:255'],
            'doha_stay' => ['nullable', 'string', 'max:255'],
            'arrival_at' => ['nullable', 'date'],
            'departure_at' => ['nullable', 'date', 'after_or_equal:arrival_at'],
            'attached_label' => ['nullable', 'string', 'max:255'],
            'transport_modes' => ['nullable', 'array'],
            'transport_modes.*' => ['string'],
            'tours' => ['nullable', 'array'],
            'tours.*' => ['string'],
        ]);

        $journey = $request->user()->journeys()->create([
            ...$data,
            'itinerary' => $action->execute($data),
        ]);

        return response()->json(['data' => new JourneyResource($journey)]);
    }
}
