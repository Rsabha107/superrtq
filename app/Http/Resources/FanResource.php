<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'fan_number' => $this->fan_number,
            'fan_id' => $this->fan_id,
            'status' => $this->status,
            'language' => $this->language,
            'birth_date' => $this->birth_date?->toDateString(),
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'country_of_residence' => $this->country_of_residence,
            'member_since' => $this->member_since?->toDateString(),
            'points_balance' => $this->points_balance,
            'preferences' => $this->preferences ?? (object) [],
        ];
    }
}
