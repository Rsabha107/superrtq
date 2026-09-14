<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RewardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Rewards/Index', [
            'rewards' => Reward::orderByDesc('created_at')->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Rewards/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        Reward::create($this->validated($request));

        return redirect()->route('admin.rewards.index')->with('success', 'Reward created.');
    }

    public function edit(Reward $reward): Response
    {
        return Inertia::render('Rewards/Edit', ['reward' => $reward]);
    }

    public function update(Request $request, Reward $reward): RedirectResponse
    {
        $reward->update($this->validated($request));

        return redirect()->route('admin.rewards.index')->with('success', 'Reward updated.');
    }

    public function destroy(Reward $reward): RedirectResponse
    {
        $reward->delete();

        return redirect()->route('admin.rewards.index')->with('success', 'Reward deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'points_cost' => ['required', 'integer', 'min:1'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
