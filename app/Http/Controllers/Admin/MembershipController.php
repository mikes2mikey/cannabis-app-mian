<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = MembershipPlan::all();
        return view('admin.memberships.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.memberships.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1'
        ]);

        MembershipPlan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership plan created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plan = MembershipPlan::findOrFail($id);
        return view('admin.memberships.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plan = MembershipPlan::findOrFail($id);
        return view('admin.memberships.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plan = MembershipPlan::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1'
        ]);
        
        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership plan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plan = MembershipPlan::findOrFail($id);
        $plan->delete();
        
        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership plan deleted successfully');
    }
}
