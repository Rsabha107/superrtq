<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LostFoundReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LostFoundController extends Controller
{
    public function index(): Response
    {
        $reports = LostFoundReport::with(['fan:id,display_name,fan_number'])
            ->latest()
            ->paginate(20)
            ->through(fn (LostFoundReport $r) => [
                'id' => $r->id,
                'fan_name' => $r->fan->display_name,
                'fan_number' => $r->fan->fan_number,
                'item_description' => $r->item_description,
                'location' => $r->location,
                'status' => $r->status,
                'created_at' => $r->created_at->toDateTimeString(),
            ]);

        return Inertia::render('LostFound/Index', ['reports' => $reports]);
    }

    public function update(Request $request, LostFoundReport $lostFoundReport): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:reported,found,closed'],
        ]);

        $lostFoundReport->update($data);

        return redirect()->route('admin.lost-found.index')->with('success', 'Report updated.');
    }
}
