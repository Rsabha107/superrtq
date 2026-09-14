<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PointsRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PointsRuleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('PointsRules/Index', [
            'pointsRules' => PointsRule::with('event:id,title')->orderByDesc('created_at')->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('PointsRules/Create', [
            'events' => Event::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        PointsRule::create($this->validated($request));

        return redirect()->route('admin.points-rules.index')->with('success', 'Points rule created.');
    }

    public function edit(PointsRule $pointsRule): Response
    {
        return Inertia::render('PointsRules/Edit', [
            'pointsRule' => $pointsRule,
            'events' => Event::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, PointsRule $pointsRule): RedirectResponse
    {
        $pointsRule->update($this->validated($request));

        return redirect()->route('admin.points-rules.index')->with('success', 'Points rule updated.');
    }

    public function destroy(PointsRule $pointsRule): RedirectResponse
    {
        $pointsRule->delete();

        return redirect()->route('admin.points-rules.index')->with('success', 'Points rule deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'points' => ['required', 'integer'],
            'event_id' => ['nullable', 'exists:events,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
