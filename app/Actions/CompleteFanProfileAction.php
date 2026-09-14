<?php

namespace App\Actions;

use App\Models\Fan;
use App\Models\PointsTransaction;
use Illuminate\Support\Facades\DB;

class CompleteFanProfileAction
{
    public const PROFILE_BONUS_POINTS = 250;

    public const PERSONALIZATION_BONUS_POINTS = 150;

    public function execute(Fan $fan, array $attributes): Fan
    {
        return DB::transaction(function () use ($fan, $attributes) {
            $fan->fill($attributes);

            if ($fan->status === 'pending_profile') {
                [$fanNumber, $fanId] = Fan::generateIdentity();

                $fan->fan_number = $fanNumber;
                $fan->fan_id = $fanId;
                $fan->member_since = now();
                $fan->status = 'verified';
                $fan->points_balance += self::PROFILE_BONUS_POINTS;

                $fan->save();

                PointsTransaction::create([
                    'fan_id' => $fan->id,
                    'type' => 'earn',
                    'points' => self::PROFILE_BONUS_POINTS,
                    'description' => 'Profile completion bonus',
                    'source_type' => 'fan_id_issuance',
                ]);
            } else {
                $fan->save();

                $alreadyEarned = PointsTransaction::where('fan_id', $fan->id)
                    ->where('source_type', 'personalization_bonus')
                    ->exists();

                if (! $alreadyEarned) {
                    $fan->points_balance += self::PERSONALIZATION_BONUS_POINTS;
                    $fan->save();

                    PointsTransaction::create([
                        'fan_id' => $fan->id,
                        'type' => 'earn',
                        'points' => self::PERSONALIZATION_BONUS_POINTS,
                        'description' => 'Personalisation bonus',
                        'source_type' => 'personalization_bonus',
                    ]);
                }
            }

            return $fan->fresh();
        });
    }
}
