<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\SubmitPredictionAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\FanResource;
use App\Http\Resources\PredictionResource;
use App\Models\Fixture;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function store(Request $request, Fixture $match, SubmitPredictionAction $action)
    {
        $data = $request->validate([
            'predicted_home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'predicted_away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $prediction = $action->execute(
            $request->user(),
            $match,
            $data['predicted_home_score'],
            $data['predicted_away_score'],
        );

        return response()->json([
            'prediction' => new PredictionResource($prediction),
            'fan' => new FanResource($prediction->fan->fresh()),
        ]);
    }

    public function mine(Request $request)
    {
        $predictions = $request->user()->predictions()
            ->with('fixture')
            ->latest()
            ->get();

        return PredictionResource::collection($predictions);
    }
}
