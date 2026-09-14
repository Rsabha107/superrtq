<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StadiumResource;
use App\Models\Stadium;

class StadiumController extends Controller
{
    public function index()
    {
        $stadiums = Stadium::where('status', 'active')
            ->orderBy('name')
            ->get();

        return StadiumResource::collection($stadiums);
    }

    public function show(string $stadium)
    {
        $model = Stadium::where('status', 'active')
            ->where(fn ($query) => $query->where('id', $stadium)->orWhere('slug', $stadium))
            ->firstOrFail();

        return new StadiumResource($model);
    }
}
