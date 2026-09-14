<?php

namespace App\Actions;

use App\Models\Fan;
use App\Models\PointsTransaction;
use App\Models\Redemption;
use App\Models\Reward;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RedeemRewardAction
{
    /**
     * Validate balance, deduct points and record the redemption atomically.
     */
    public function execute(Fan $fan, Reward $reward): Redemption
    {
        return DB::transaction(function () use ($fan, $reward) {
            /** @var Fan $fan */
            $fan = Fan::query()->lockForUpdate()->findOrFail($fan->id);
            /** @var Reward $reward */
            $reward = Reward::query()->lockForUpdate()->findOrFail($reward->id);

            if (! $reward->isAvailable()) {
                throw ValidationException::withMessages([
                    'reward' => 'This reward is no longer available.',
                ]);
            }

            if ($fan->points_balance < $reward->points_cost) {
                throw ValidationException::withMessages([
                    'points' => 'You do not have enough points to redeem this reward.',
                ]);
            }

            $fan->points_balance -= $reward->points_cost;
            $fan->save();

            if ($reward->quantity !== null) {
                $reward->decrement('quantity');
            }

            $redemption = Redemption::create([
                'fan_id' => $fan->id,
                'reward_id' => $reward->id,
                'points_cost' => $reward->points_cost,
                'status' => 'completed',
                'redeemed_at' => now(),
            ]);

            PointsTransaction::create([
                'fan_id' => $fan->id,
                'type' => 'redeem',
                'points' => -$reward->points_cost,
                'description' => "Redeemed: {$reward->title}",
                'source_type' => 'reward_redemption',
                'source_id' => $redemption->id,
            ]);

            return $redemption->fresh(['reward']);
        });
    }
}
