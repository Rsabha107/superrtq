<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redemption;
use Inertia\Inertia;
use Inertia\Response;

class RedemptionController extends Controller
{
    public function index(): Response
    {
        $redemptions = Redemption::with(['fan:id,display_name,fan_number', 'reward:id,title'])
            ->orderByDesc('redeemed_at')
            ->paginate(20)
            ->through(fn (Redemption $r) => [
                'id' => $r->id,
                'fan_name' => $r->fan->display_name,
                'fan_number' => $r->fan->fan_number,
                'reward_title' => $r->reward->title,
                'points_cost' => $r->points_cost,
                'status' => $r->status,
                'redeemed_at' => $r->redeemed_at->toDateTimeString(),
            ]);

        return Inertia::render('Redemptions/Index', ['redemptions' => $redemptions]);
    }
}
