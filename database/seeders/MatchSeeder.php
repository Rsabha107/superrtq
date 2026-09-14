<?php

namespace Database\Seeders;

use App\Models\Fixture;
use Illuminate\Database\Seeder;

/**
 * A fictional "Road to Qatar" warm-up mini-tournament — not a claim about
 * any real official group draw or result, just realistic demo data for
 * the Match Centre / group standings / predictions features.
 */
class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $fixtures = [
            // Group A — finished, feeds the standings table.
            ['sport' => 'football', 'group_name' => 'Group A', 'home_team' => 'Qatar', 'away_team' => 'Jordan', 'venue' => 'Lusail Stadium, Lusail', 'kickoff_at' => '2026-08-20 19:00:00', 'home_score' => 2, 'away_score' => 1, 'status' => 'finished'],
            ['sport' => 'football', 'group_name' => 'Group A', 'home_team' => 'Iraq', 'away_team' => 'Australia', 'venue' => 'Al Bayt Stadium, Al Khor', 'kickoff_at' => '2026-08-20 21:30:00', 'home_score' => 0, 'away_score' => 0, 'status' => 'finished'],
            ['sport' => 'football', 'group_name' => 'Group A', 'home_team' => 'Qatar', 'away_team' => 'Iraq', 'venue' => 'Lusail Stadium, Lusail', 'kickoff_at' => '2026-08-24 19:00:00', 'home_score' => 1, 'away_score' => 1, 'status' => 'finished'],
            ['sport' => 'football', 'group_name' => 'Group A', 'home_team' => 'Jordan', 'away_team' => 'Australia', 'venue' => 'Al Bayt Stadium, Al Khor', 'kickoff_at' => '2026-08-24 21:30:00', 'home_score' => 3, 'away_score' => 2, 'status' => 'finished'],

            // Group A — upcoming, open for prediction.
            ['sport' => 'football', 'group_name' => 'Group A', 'home_team' => 'Qatar', 'away_team' => 'Australia', 'venue' => 'Lusail Stadium, Lusail', 'kickoff_at' => '2026-09-20 19:00:00', 'home_score' => null, 'away_score' => null, 'status' => 'scheduled', 'is_featured' => true],
            ['sport' => 'football', 'group_name' => 'Group A', 'home_team' => 'Jordan', 'away_team' => 'Iraq', 'venue' => 'Al Bayt Stadium, Al Khor', 'kickoff_at' => '2026-09-20 21:30:00', 'home_score' => null, 'away_score' => null, 'status' => 'scheduled'],

            // Group B — finished.
            ['sport' => 'football', 'group_name' => 'Group B', 'home_team' => 'Saudi Arabia', 'away_team' => 'UAE', 'venue' => 'Education City Stadium, Al Rayyan', 'kickoff_at' => '2026-08-21 19:00:00', 'home_score' => 1, 'away_score' => 0, 'status' => 'finished'],
            ['sport' => 'football', 'group_name' => 'Group B', 'home_team' => 'Japan', 'away_team' => 'South Korea', 'venue' => 'Khalifa International Stadium, Al Rayyan', 'kickoff_at' => '2026-08-21 21:30:00', 'home_score' => 2, 'away_score' => 2, 'status' => 'finished'],
            ['sport' => 'football', 'group_name' => 'Group B', 'home_team' => 'Saudi Arabia', 'away_team' => 'Japan', 'venue' => 'Education City Stadium, Al Rayyan', 'kickoff_at' => '2026-08-25 19:00:00', 'home_score' => 2, 'away_score' => 1, 'status' => 'finished'],
            ['sport' => 'football', 'group_name' => 'Group B', 'home_team' => 'UAE', 'away_team' => 'South Korea', 'venue' => 'Khalifa International Stadium, Al Rayyan', 'kickoff_at' => '2026-08-25 21:30:00', 'home_score' => 0, 'away_score' => 1, 'status' => 'finished'],

            // Group B — upcoming.
            ['sport' => 'football', 'group_name' => 'Group B', 'home_team' => 'Saudi Arabia', 'away_team' => 'South Korea', 'venue' => 'Education City Stadium, Al Rayyan', 'kickoff_at' => '2026-09-21 19:00:00', 'home_score' => null, 'away_score' => null, 'status' => 'scheduled'],
            ['sport' => 'football', 'group_name' => 'Group B', 'home_team' => 'UAE', 'away_team' => 'Japan', 'venue' => 'Khalifa International Stadium, Al Rayyan', 'kickoff_at' => '2026-09-21 21:30:00', 'home_score' => null, 'away_score' => null, 'status' => 'scheduled'],

            // Basketball — ungrouped, mirrors the NBA Qatar Games event.
            ['sport' => 'basketball', 'group_name' => null, 'home_team' => 'Miami Heat', 'away_team' => 'Cleveland Cavaliers', 'venue' => 'Lusail Multipurpose Hall, Lusail', 'kickoff_at' => '2026-10-02 18:00:00', 'home_score' => null, 'away_score' => null, 'status' => 'scheduled', 'is_featured' => true],
        ];

        foreach ($fixtures as $fixture) {
            Fixture::updateOrCreate(
                [
                    'home_team' => $fixture['home_team'],
                    'away_team' => $fixture['away_team'],
                    'kickoff_at' => $fixture['kickoff_at'],
                ],
                $fixture + ['is_featured' => $fixture['is_featured'] ?? false],
            );
        }
    }
}
