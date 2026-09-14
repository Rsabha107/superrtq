<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FanResource;
use App\Http\Resources\PointsTransactionResource;
use Illuminate\Http\Request;

class FanController extends Controller
{
    public function show(Request $request)
    {
        return new FanResource($request->user());
    }

    public function points(Request $request)
    {
        return response()->json([
            'points_balance' => $request->user()->points_balance,
        ]);
    }

    public function pointsTransactions(Request $request)
    {
        $transactions = $request->user()
            ->pointsTransactions()
            ->orderByDesc('created_at')
            ->paginate(20);

        return PointsTransactionResource::collection($transactions);
    }
}
