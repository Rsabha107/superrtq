<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fixture;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MatchController extends Controller
{
    public function index(): Response
    {
        $matches = Fixture::orderByDesc('kickoff_at')->paginate(15);

        return Inertia::render('Matches/Index', ['matches' => $matches]);
    }

    public function create(): Response
    {
        return Inertia::render('Matches/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        Fixture::create($this->validated($request));

        return redirect()->route('admin.matches.index')->with('success', 'Fixture created.');
    }

    public function edit(Fixture $match): Response
    {
        return Inertia::render('Matches/Edit', ['match' => $match]);
    }

    public function update(Request $request, Fixture $match): RedirectResponse
    {
        $match->update($this->validated($request));

        return redirect()->route('admin.matches.index')->with('success', 'Fixture updated.');
    }

    public function destroy(Fixture $match): RedirectResponse
    {
        $match->delete();

        return redirect()->route('admin.matches.index')->with('success', 'Fixture deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'sport' => ['required', 'string', 'max:100'],
            'group_name' => ['nullable', 'string', 'max:100'],
            'home_team' => ['required', 'string', 'max:255'],
            'away_team' => ['required', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'kickoff_at' => ['required', 'date'],
            'home_score' => ['nullable', 'integer', 'min:0'],
            'away_score' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:scheduled,finished'],
            'is_featured' => ['boolean'],
        ]);
    }
}
