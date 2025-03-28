<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PlantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $plants = Auth::user()->isAdmin() 
            ? Plant::with(['users', 'growthLogs'])->latest()->paginate(10)
            : Auth::user()->plants()->with('growthLogs')->latest()->paginate(10);

        return view('plants.index', compact('plants'));
    }

    public function show(Plant $plant)
    {
        if (!Auth::user()->isAdmin() && !$plant->users->contains(Auth::id())) {
            abort(403);
        }

        $plant->load(['users', 'growthLogs', 'images']);
        return view('plants.show', compact('plant'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $members = User::where('role', 'member')->orderBy('name')->get();
        return view('plants.create', compact('members'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'strain' => 'required|string|max:255',
            'planting_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $plant = Plant::create([
            'strain' => $validated['strain'],
            'planting_date' => $validated['planting_date'],
            'notes' => $validated['notes']
        ]);

        $plant->users()->attach($validated['user_ids']);

        return redirect()->route('plants.index')
            ->with('success', 'Plant created successfully.');
    }

    public function edit(Plant $plant)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $members = User::where('role', 'member')->orderBy('name')->get();
        return view('plants.edit', compact('plant', 'members'));
    }

    public function update(Request $request, Plant $plant)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'strain' => 'required|string|max:255',
            'planting_date' => 'required|date',
            'harvest_date' => 'nullable|date',
            'status' => 'required|in:growing,harvested,cancelled',
            'notes' => 'nullable|string'
        ]);

        \Log::info('Update Plant Request Data:', [
            'request_all' => $request->all(),
            'validated' => $validated,
            'user_ids' => $request->input('user_ids'),
        ]);

        $plant->update([
            'strain' => $validated['strain'],
            'planting_date' => $validated['planting_date'],
            'harvest_date' => $validated['harvest_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes']
        ]);

        $plant->users()->sync($validated['user_ids']);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Plant updated successfully.');
    }

    public function destroy(Plant $plant)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $plant->delete();

        return redirect()->route('plants.index')
            ->with('success', 'Plant deleted successfully.');
    }
} 