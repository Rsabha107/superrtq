<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'published')
            ->orderBy('start_at')
            ->get();

        return EventResource::collection($events);
    }

    public function show(string $event)
    {
        $model = Event::where('status', 'published')
            ->where(fn ($query) => $query->where('id', $event)->orWhere('slug', $event))
            ->firstOrFail();

        return new EventResource($model);
    }
}
