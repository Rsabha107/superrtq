<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fan;
use Inertia\Inertia;
use Inertia\Response;

class FanController extends Controller
{
    public function index(): Response
    {
        $fans = Fan::orderByDesc('created_at')
            ->paginate(15)
            ->through(fn (Fan $fan) => [
                'id' => $fan->id,
                'display_name' => $fan->display_name,
                'email' => $fan->email,
                'mobile' => $fan->mobile,
                'fan_number' => $fan->fan_number,
                'status' => $fan->status,
                'points_balance' => $fan->points_balance,
                'member_since' => $fan->member_since?->toDateString(),
            ]);

        return Inertia::render('Fans/Index', ['fans' => $fans]);
    }

    public function show(Fan $fan): Response
    {
        $fan->load([
            'pointsTransactions' => fn ($query) => $query->orderByDesc('created_at')->limit(50),
            'redemptions' => fn ($query) => $query->with('reward')->orderByDesc('redeemed_at'),
        ]);

        return Inertia::render('Fans/Show', [
            'fan' => [
                'id' => $fan->id,
                'display_name' => $fan->display_name,
                'email' => $fan->email,
                'mobile' => $fan->mobile,
                'fan_number' => $fan->fan_number,
                'fan_id' => $fan->fan_id,
                'status' => $fan->status,
                'language' => $fan->language,
                'points_balance' => $fan->points_balance,
                'member_since' => $fan->member_since?->toDateString(),
                'preferences' => $fan->preferences ?? (object) [],
            ],
            'pointsTransactions' => $fan->pointsTransactions->map(fn ($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'points' => $t->points,
                'description' => $t->description,
                'created_at' => $t->created_at?->toDateTimeString(),
            ]),
            'redemptions' => $fan->redemptions->map(fn ($r) => [
                'id' => $r->id,
                'reward_title' => $r->reward->title,
                'points_cost' => $r->points_cost,
                'status' => $r->status,
                'redeemed_at' => $r->redeemed_at?->toDateTimeString(),
            ]),
        ]);
    }
}
