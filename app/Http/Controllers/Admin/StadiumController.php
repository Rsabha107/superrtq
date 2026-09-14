<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stadium;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class StadiumController extends Controller
{
    public function index(): Response
    {
        $stadiums = Stadium::orderBy('name')->paginate(15);

        return Inertia::render('Stadiums/Index', ['stadiums' => $stadiums]);
    }

    public function create(): Response
    {
        return Inertia::render('Stadiums/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(6);

        Stadium::create($data);

        return redirect()->route('admin.stadiums.index')->with('success', 'Stadium created.');
    }

    public function edit(Stadium $stadium): Response
    {
        return Inertia::render('Stadiums/Edit', ['stadium' => $stadium]);
    }

    public function update(Request $request, Stadium $stadium): RedirectResponse
    {
        $stadium->update($this->validated($request));

        return redirect()->route('admin.stadiums.index')->with('success', 'Stadium updated.');
    }

    public function destroy(Stadium $stadium): RedirectResponse
    {
        $stadium->delete();

        return redirect()->route('admin.stadiums.index')->with('success', 'Stadium deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
