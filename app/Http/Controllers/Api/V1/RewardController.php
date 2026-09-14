<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\RedeemRewardAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\FanResource;
use App\Http\Resources\RedemptionResource;
use App\Http\Resources\RewardResource;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::where('status', 'active')
            ->orderBy('points_cost')
            ->get();

        return RewardResource::collection($rewards);
    }

    public function show(Reward $reward)
    {
        return new RewardResource($reward);
    }

    public function redeem(Request $request, Reward $reward, RedeemRewardAction $action)
    {
        $redemption = $action->execute($request->user(), $reward);

        return response()->json([
            'redemption' => new RedemptionResource($redemption),
            'fan' => new FanResource($redemption->fan->fresh()),
        ]);
    }
}
