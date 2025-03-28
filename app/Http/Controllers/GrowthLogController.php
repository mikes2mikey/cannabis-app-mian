<?php

namespace App\Http\Controllers;

use App\Models\GrowthLog;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrowthLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Plant $plant)
    {
        if (!Auth::user()->isAdmin() && !$plant->users->contains(Auth::id())) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'required|string',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'phase' => 'required|in:seedling,vegetative,flowering,harvest',
            'temperature' => 'nullable|numeric|min:-10|max:50',
            'humidity' => 'nullable|numeric|min:0|max:100'
        ]);

        $plant->growthLogs()->create($validated);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Growth log added successfully.');
    }

    public function edit(GrowthLog $growthLog)
    {
        if (!Auth::user()->isAdmin() && !$growthLog->plant->users->contains(Auth::id())) {
            abort(403);
        }

        return view('growth-logs.edit', compact('growthLog'));
    }

    public function update(Request $request, GrowthLog $growthLog)
    {
        if (!Auth::user()->isAdmin() && !$growthLog->plant->users->contains(Auth::id())) {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'required|string',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'phase' => 'required|in:seedling,vegetative,flowering,harvest',
            'temperature' => 'nullable|numeric|min:-10|max:50',
            'humidity' => 'nullable|numeric|min:0|max:100'
        ]);

        $growthLog->update($validated);

        return redirect()->route('plants.show', $growthLog->plant)
            ->with('success', 'Growth log updated successfully.');
    }

    public function destroy(GrowthLog $growthLog)
    {
        if (!Auth::user()->isAdmin() && !$growthLog->plant->users->contains(Auth::id())) {
            abort(403);
        }

        $plant = $growthLog->plant;
        $growthLog->delete();

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Growth log deleted successfully.');
    }
} 