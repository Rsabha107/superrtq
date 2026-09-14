<?php

namespace App\Actions;

use App\Models\Fan;
use App\Models\GameSession;
use App\Models\PointsTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecordGameSessionAction
{
    /**
     * Max valid score and points-per-point for each game. Score is
     * clamped server-side before computing points, so a malformed or
     * malicious client score can't inflate the award beyond the game's
     * real maximum (50 points either way).
     */
    private const GAMES = [
        'flag_quiz' => ['max_score' => 10, 'points_per' => 5],
        'penalty_shootout' => ['max_score' => 5, 'points_per' => 10],
        'flappy_dunk' => ['max_score' => 5, 'points_per' => 10],
    ];

    /**
     * Mini-games are replayable for points every session, with no daily
     * cap — an acceptable trust level for this POC (matches how the rest
     * of this backend treats client input), but a real production build
     * would want a cap here.
     */
    public function execute(Fan $fan, string $game, int $score): GameSession
    {
        if (! array_key_exists($game, self::GAMES)) {
            throw ValidationException::withMessages([
                'game' => 'Unknown game.',
            ]);
        }

        $config = self::GAMES[$game];
        $clampedScore = max(0, min($score, $config['max_score']));
        $points = $clampedScore * $config['points_per'];
        $maxScore = $config['max_score'];

        return DB::transaction(function () use ($fan, $game, $clampedScore, $points, $maxScore) {
            $session = GameSession::create([
                'fan_id' => $fan->id,
                'game' => $game,
                'score' => $clampedScore,
                'points_awarded' => $points,
            ]);

            /** @var Fan $fan */
            $fan = Fan::query()->lockForUpdate()->findOrFail($fan->id);
            $fan->points_balance += $points;
            $fan->save();

            PointsTransaction::create([
                'fan_id' => $fan->id,
                'type' => 'earn',
                'points' => $points,
                'description' => $this->describe($game, $clampedScore, $maxScore),
                'source_type' => 'mini_game',
                'source_id' => $session->id,
            ]);

            return $session->fresh();
        });
    }

    private function describe(string $game, int $score, int $maxScore): string
    {
        $label = match ($game) {
            'flag_quiz' => 'Flag Quiz',
            'penalty_shootout' => 'Penalty Shootout',
            'flappy_dunk' => 'Flappy Dunk',
            default => $game,
        };

        return "{$label}: {$score}/{$maxScore}";
    }
}
