<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Fan;
use App\Models\PointsTransaction;
use App\Models\Redemption;
use App\Models\Reward;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'fans' => Fan::count(),
                'verified_fans' => Fan::where('status', 'verified')->count(),
                'events' => Event::where('status', 'published')->count(),
                'rewards' => Reward::where('status', 'active')->count(),
                'redemptions' => Redemption::count(),
                'points_issued' => (int) PointsTransaction::where('points', '>', 0)->sum('points'),
                'points_redeemed' => (int) abs(PointsTransaction::where('points', '<', 0)->sum('points')),
            ],
            'recentRedemptions' => Redemption::with(['fan', 'reward'])
                ->latest('redeemed_at')
                ->take(5)
                ->get()
                ->map(fn (Redemption $r) => [
                    'id' => $r->id,
                    'fan_name' => $r->fan->display_name,
                    'reward_title' => $r->reward->title,
                    'points_cost' => $r->points_cost,
                    'redeemed_at' => $r->redeemed_at->toDateTimeString(),
                ]),
            'upcomingEvents' => Event::where('status', 'published')
                ->where('start_at', '>=', now())
                ->orderBy('start_at')
                ->take(5)
                ->get(['id', 'title', 'event_type', 'start_at']),
        ]);
    }
}
