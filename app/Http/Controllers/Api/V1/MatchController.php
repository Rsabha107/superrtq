<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FixtureResource;
use App\Models\Fixture;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $fixtures = $this->scoped($request)
            ->orderBy('kickoff_at')
            ->get();

        return FixtureResource::collection($fixtures);
    }

    public function show(Fixture $match)
    {
        return new FixtureResource($match);
    }

    /**
     * Group standings computed from finished fixtures only — no stored
     * standings table, so results are always consistent with the fixture
     * data itself.
     */
    public function groups(Request $request)
    {
        $fixtures = Fixture::query()
            ->when($request->query('sport'), fn ($query, $sport) => $query->where('sport', $sport))
            ->whereNotNull('group_name')
            ->where('status', 'finished')
            ->get();

        $groups = [];

        foreach ($fixtures->groupBy('group_name') as $groupName => $groupFixtures) {
            $teams = [];

            foreach ($groupFixtures as $fixture) {
                foreach ([
                    ['team' => $fixture->home_team, 'for' => $fixture->home_score, 'against' => $fixture->away_score],
                    ['team' => $fixture->away_team, 'for' => $fixture->away_score, 'against' => $fixture->home_score],
                ] as $side) {
                    $team = $side['team'];
                    $teams[$team] ??= [
                        'team' => $team,
                        'played' => 0,
                        'won' => 0,
                        'drawn' => 0,
                        'lost' => 0,
                        'goals_for' => 0,
                        'goals_against' => 0,
                        'points' => 0,
                    ];

                    $teams[$team]['played']++;
                    $teams[$team]['goals_for'] += $side['for'];
                    $teams[$team]['goals_against'] += $side['against'];

                    if ($side['for'] > $side['against']) {
                        $teams[$team]['won']++;
                        $teams[$team]['points'] += 3;
                    } elseif ($side['for'] === $side['against']) {
                        $teams[$team]['drawn']++;
                        $teams[$team]['points'] += 1;
                    } else {
                        $teams[$team]['lost']++;
                    }
                }
            }

            $standings = collect($teams)
                ->map(function ($row) {
                    $row['goal_difference'] = $row['goals_for'] - $row['goals_against'];

                    return $row;
                })
                ->sortByDesc(fn ($row) => [$row['points'], $row['goal_difference'], $row['goals_for']])
                ->values();

            $groups[] = [
                'group_name' => $groupName,
                'standings' => $standings,
            ];
        }

        return response()->json(['data' => $groups]);
    }

    private function scoped(Request $request)
    {
        return Fixture::query()
            ->when($request->query('sport'), fn ($query, $sport) => $query->where('sport', $sport));
    }
}
