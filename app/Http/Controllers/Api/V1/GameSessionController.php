<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\RecordGameSessionAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\FanResource;
use App\Http\Resources\GameSessionResource;
use Illuminate\Http\Request;

class GameSessionController extends Controller
{
    public function store(Request $request, RecordGameSessionAction $action)
    {
        $data = $request->validate([
            'game' => ['required', 'string', 'in:flag_quiz,penalty_shootout,flappy_dunk'],
            'score' => ['required', 'integer', 'min:0'],
        ]);

        $session = $action->execute($request->user(), $data['game'], $data['score']);

        return response()->json([
            'session' => new GameSessionResource($session),
            'fan' => new FanResource($session->fan->fresh()),
        ]);
    }
}
