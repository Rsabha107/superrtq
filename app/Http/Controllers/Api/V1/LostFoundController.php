<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LostFoundReportResource;
use Illuminate\Http\Request;

class LostFoundController extends Controller
{
    public function index(Request $request)
    {
        $reports = $request->user()->lostFoundReports()->latest()->get();

        return LostFoundReportResource::collection($reports);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_description' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'date_lost' => ['nullable', 'date'],
        ]);

        $report = $request->user()->lostFoundReports()->create([
            ...$data,
            'status' => 'reported',
        ]);

        return response()->json(['data' => new LostFoundReportResource($report)]);
    }
}
