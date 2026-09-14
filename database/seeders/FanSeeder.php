<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Fan;
use App\Models\PointsTransaction;
use App\Models\Redemption;
use App\Models\Reward;
use Illuminate\Database\Seeder;

class FanSeeder extends Seeder
{
    public function run(): void
    {
        $interestedEvents = Event::whereIn('slug', [
            'qatar-vs-jordan',
            'nba-qatar-games-heat-vs-cavaliers',
        ])->pluck('slug')->all();

        $fan = Fan::updateOrCreate(
            ['fan_number' => '8842'],
            [
                'display_name' => 'Yousef Al Attiyah',
                'first_name' => 'Yousef',
                'last_name' => 'Al Attiyah',
                'email' => 'yousef.alattiyah@example.qa',
                'mobile' => '+974 5512 3456',
                'fan_number' => '8842',
                'fan_id' => 'QA-8842-1176',
                'status' => 'verified',
                'language' => 'en',
                'birth_date' => '1995-01-17',
                'nationality' => 'Qatar',
                'gender' => 'male',
                'country_of_residence' => 'Qatar',
                'member_since' => '2026-03-01',
                'points_balance' => 4820,
                'preferences' => [
                    'sports' => ['Football', 'Basketball', 'Padel'],
                    'interested_events' => $interestedEvents,
                    'interests' => ['Live music', 'Family days', 'Fan zones'],
                ],
            ]
        );

        $dining = Reward::where('title', '10% Partner Dining Voucher')->first();

        $transactions = [
            ['type' => 'earn', 'points' => 4000, 'description' => 'Founding Fan welcome bonus — Fan Number 8842', 'source_type' => 'fan_id_issuance', 'created_at' => '2026-03-01 09:00:00'],
            ['type' => 'earn', 'points' => 150, 'description' => 'Attended Qatar vs Jordan — Lusail Stadium', 'source_type' => 'points_rule', 'created_at' => '2026-04-12 21:40:00'],
            ['type' => 'earn', 'points' => 200, 'description' => 'Attended NBA Qatar Games — Heat vs Cavaliers', 'source_type' => 'points_rule', 'created_at' => '2026-05-20 20:15:00'],
            ['type' => 'earn', 'points' => 120, 'description' => 'Attended Amir Cup Equestrian Show — Al Shaqab', 'source_type' => 'points_rule', 'created_at' => '2026-06-08 17:30:00'],
            ['type' => 'redeem', 'points' => -500, 'description' => 'Redeemed: 10% Partner Dining Voucher', 'source_type' => 'reward_redemption', 'source_id' => $dining?->id, 'created_at' => '2026-06-15 12:05:00'],
            ['type' => 'earn', 'points' => 50, 'description' => 'Fan Zone check-in bonus', 'source_type' => 'points_rule', 'created_at' => '2026-07-02 18:20:00'],
            ['type' => 'earn', 'points' => 100, 'description' => 'Shared Fan ID with a friend', 'source_type' => 'points_rule', 'created_at' => '2026-07-19 10:00:00'],
            ['type' => 'earn', 'points' => 700, 'description' => 'RTQ+ app loyalty streak bonus', 'source_type' => 'loyalty_bonus', 'created_at' => '2026-08-25 08:00:00'],
        ];

        foreach ($transactions as $transaction) {
            PointsTransaction::updateOrCreate(
                [
                    'fan_id' => $fan->id,
                    'description' => $transaction['description'],
                ],
                $transaction + ['fan_id' => $fan->id]
            );
        }

        if ($dining) {
            Redemption::updateOrCreate(
                [
                    'fan_id' => $fan->id,
                    'reward_id' => $dining->id,
                ],
                [
                    'points_cost' => 500,
                    'status' => 'completed',
                    'redeemed_at' => '2026-06-15 12:05:00',
                ]
            );
        }
    }
}
