<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $rewards = [
            [
                'title' => '10% Partner Dining Voucher',
                'description' => "10% off your bill at participating RTQ+ partner restaurants across Doha. Valid for 30 days after redemption.",
                'image' => null,
                'points_cost' => 500,
                'quantity' => 200,
                'status' => 'active',
            ],
            [
                'title' => 'Official RTQ+ Cap',
                'description' => "Maroon and amber RTQ+ cap, adjustable strap. Collect from any RTQ+ Fan Zone kiosk.",
                'image' => null,
                'points_cost' => 800,
                'quantity' => 40,
                'status' => 'active',
            ],
            [
                'title' => 'Fan Zone Priority Lane Upgrade',
                'description' => "Skip the queue with priority lane access at your next RTQ+ Fan Zone visit.",
                'image' => null,
                'points_cost' => 1200,
                'quantity' => 30,
                'status' => 'active',
            ],
            [
                'title' => 'RTQ+ Tracksuit',
                'description' => "Official RTQ+ training tracksuit, available in sizes S–XXL while stock lasts.",
                'image' => null,
                'points_cost' => 3200,
                'quantity' => 15,
                'status' => 'active',
            ],
            [
                'title' => 'Stadium Tour for Two',
                'description' => "Behind-the-scenes tour of Lusail Stadium for you and a guest, including changing rooms and pitch-side access.",
                'image' => null,
                'points_cost' => 2500,
                'quantity' => 10,
                'status' => 'active',
            ],
            [
                'title' => 'Courtside Meet & Greet — NBA Qatar Games',
                'description' => "Courtside meet-and-greet pass for the NBA Qatar Games, subject to availability on the day.",
                'image' => null,
                'points_cost' => 4500,
                'quantity' => 5,
                'status' => 'active',
            ],
        ];

        foreach ($rewards as $reward) {
            Reward::updateOrCreate(
                ['title' => $reward['title']],
                $reward
            );
        }
    }
}
