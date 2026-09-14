<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\PointsRule;
use Illuminate\Database\Seeder;

class PointsRuleSeeder extends Seeder
{
    public function run(): void
    {
        $qatarVsJordan = Event::where('slug', 'qatar-vs-jordan')->first();
        $nbaGames = Event::where('slug', 'nba-qatar-games-heat-vs-cavaliers')->first();

        $rules = [
            [
                'name' => 'Fan ID activation',
                'description' => 'Awarded automatically when a new Fan ID is issued.',
                'points' => 250,
                'event_id' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Fan Zone check-in',
                'description' => 'Scan your Fan ID QR at any RTQ+ Fan Zone kiosk.',
                'points' => 50,
                'event_id' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Share your Fan ID',
                'description' => 'Share your digital Fan ID card once per season.',
                'points' => 100,
                'event_id' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Attend Qatar vs Jordan',
                'description' => 'Check in at Lusail Stadium for the qualifier.',
                'points' => 150,
                'event_id' => $qatarVsJordan?->id,
                'status' => 'active',
            ],
            [
                'name' => 'Attend NBA Qatar Games',
                'description' => 'Check in at the Lusail Multipurpose Hall.',
                'points' => 200,
                'event_id' => $nbaGames?->id,
                'status' => 'active',
            ],
        ];

        foreach ($rules as $rule) {
            PointsRule::updateOrCreate(
                ['name' => $rule['name']],
                $rule
            );
        }
    }
}
