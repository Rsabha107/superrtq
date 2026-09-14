<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use Inertia\Inertia;
use Inertia\Response;

class GameSessionController extends Controller
{
    public function index(): Response
    {
        $sessions = GameSession::with(['fan:id,display_name,fan_number'])
            ->latest()
            ->paginate(20)
            ->through(fn (GameSession $s) => [
                'id' => $s->id,
                'fan_name' => $s->fan->display_name,
                'fan_number' => $s->fan->fan_number,
                'game' => $s->game,
                'score' => $s->score,
                'points_awarded' => $s->points_awarded,
                'created_at' => $s->created_at->toDateTimeString(),
            ]);

        return Inertia::render('GameSessions/Index', ['sessions' => $sessions]);
    }
}
