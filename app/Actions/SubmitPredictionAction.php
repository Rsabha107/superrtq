<?php

namespace App\Actions;

use App\Models\Fan;
use App\Models\Fixture;
use App\Models\PointsTransaction;
use App\Models\Prediction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubmitPredictionAction
{
    public const PREDICTION_BONUS_POINTS = 20;

    /**
     * Record a fan's scoreline prediction and award a fixed participation
     * bonus. Predictions are for engagement only — there is no live-result
     * scoring in this POC, so the bonus is the same regardless of accuracy.
     */
    public function execute(Fan $fan, Fixture $fixture, int $predictedHomeScore, int $predictedAwayScore): Prediction
    {
        return DB::transaction(function () use ($fan, $fixture, $predictedHomeScore, $predictedAwayScore) {
            /** @var Fixture $fixture */
            $fixture = Fixture::query()->lockForUpdate()->findOrFail($fixture->id);

            if ($fixture->status !== 'scheduled' || now()->greaterThanOrEqualTo($fixture->kickoff_at)) {
                throw ValidationException::withMessages([
                    'match' => 'Predictions are closed for this fixture.',
                ]);
            }

            if (Prediction::where('fan_id', $fan->id)->where('match_id', $fixture->id)->exists()) {
                throw ValidationException::withMessages([
                    'match' => 'You have already predicted this fixture.',
                ]);
            }

            $prediction = Prediction::create([
                'fan_id' => $fan->id,
                'match_id' => $fixture->id,
                'predicted_home_score' => $predictedHomeScore,
                'predicted_away_score' => $predictedAwayScore,
                'points_awarded' => self::PREDICTION_BONUS_POINTS,
            ]);

            /** @var Fan $fan */
            $fan = Fan::query()->lockForUpdate()->findOrFail($fan->id);
            $fan->points_balance += self::PREDICTION_BONUS_POINTS;
            $fan->save();

            PointsTransaction::create([
                'fan_id' => $fan->id,
                'type' => 'earn',
                'points' => self::PREDICTION_BONUS_POINTS,
                'description' => "Match prediction: {$fixture->home_team} vs {$fixture->away_team}",
                'source_type' => 'match_prediction',
                'source_id' => $prediction->id,
            ]);

            return $prediction->fresh(['fixture']);
        });
    }
}
