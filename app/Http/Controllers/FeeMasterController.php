<?php

namespace App\Http\Controllers;

use App\Models\FeeMaster;
use Illuminate\Http\Request;

class FeeMasterController extends Controller
{
    /**
     * Display all fee masters.
     */
    public function index()
    {
        $fees = FeeMaster::orderBy('department')
            ->orderBy('year')
            ->orderBy('fee_name')
            ->get();

        return view('fee-masters.index', compact('fees'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('fee-masters.create');
    }

    /**
     * Store new fee master.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department' => 'required|string|max:100',
            'year'       => 'required|string|max:20',
            'category'   => 'required|string|max:100',
            'fee_name'   => 'required|string|max:255',
            'amount'     => 'required|numeric|min:1',
        ]);

        $exists = FeeMaster::where('department', $validated['department'])
            ->where('year', $validated['year'])
            ->where('category', $validated['category'])
            ->where('fee_name', $validated['fee_name'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'fee_name' => 'This fee already exists for the selected Department, Year and Category.'
                ]);
        }

        FeeMaster::create($validated);

        return redirect()
            ->route('fee-masters.index')
            ->with('success', 'Fee Master created successfully.');
    }

    /**
     * Show fee details.
     */
    public function show(FeeMaster $feeMaster)
    {
        return view('fee-masters.show', compact('feeMaster'));
    }

    /**
     * Show edit form.
     */
    public function edit(FeeMaster $feeMaster)
    {
        return view('fee-masters.edit', compact('feeMaster'));
    }

    /**
     * Update fee master.
     */
    public function update(Request $request, FeeMaster $feeMaster)
    {
        $validated = $request->validate([
            'department' => 'required|string|max:100',
            'year'       => 'required|string|max:20',
            'category'   => 'required|string|max:100',
            'fee_name'   => 'required|string|max:255',
            'amount'     => 'required|numeric|min:1',
        ]);

        $exists = FeeMaster::where('department', $validated['department'])
            ->where('year', $validated['year'])
            ->where('category', $validated['category'])
            ->where('fee_name', $validated['fee_name'])
            ->where('id', '!=', $feeMaster->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'fee_name' => 'This fee already exists for the selected Department, Year and Category.'
                ]);
        }

        $feeMaster->update($validated);

        return redirect()
            ->route('fee-masters.index')
            ->with('success', 'Fee Master updated successfully.');
    }

    /**
     * Delete fee master.
     */
    public function destroy(FeeMaster $feeMaster)
    {
        $feeMaster->delete();

        return redirect()
            ->route('fee-masters.index')
            ->with('success', 'Fee Master deleted successfully.');
    }
}