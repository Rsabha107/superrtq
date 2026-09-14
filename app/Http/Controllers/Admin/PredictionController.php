<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prediction;
use Inertia\Inertia;
use Inertia\Response;

class PredictionController extends Controller
{
    public function index(): Response
    {
        $predictions = Prediction::with(['fan:id,display_name,fan_number', 'fixture:id,home_team,away_team,kickoff_at'])
            ->latest()
            ->paginate(20)
            ->through(fn (Prediction $p) => [
                'id' => $p->id,
                'fan_name' => $p->fan->display_name,
                'fan_number' => $p->fan->fan_number,
                'fixture' => "{$p->fixture->home_team} vs {$p->fixture->away_team}",
                'kickoff_at' => $p->fixture->kickoff_at->toDateTimeString(),
                'predicted_score' => "{$p->predicted_home_score}–{$p->predicted_away_score}",
                'points_awarded' => $p->points_awarded,
                'created_at' => $p->created_at->toDateTimeString(),
            ]);

        return Inertia::render('Predictions/Index', ['predictions' => $predictions]);
    }
}
