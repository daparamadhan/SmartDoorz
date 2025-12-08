<?php

namespace App\Http\Controllers;

use App\Models\DashboardStat;
use Illuminate\Http\Request;

class DashboardStatController extends Controller
{
    public function index()
    {
        $stats = DashboardStat::all();
        return view('dashboard.index', compact('stats'));
    }

    public function create()
    {
        return view('dashboard.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        DashboardStat::create($validated);

        return redirect()->route('dashboard.index')->with('success', 'Stat berhasil ditambahkan');
    }

    public function edit(DashboardStat $dashboard)
    {
        return view('dashboard.edit', ['stat' => $dashboard]);
    }

    public function update(Request $request, DashboardStat $dashboard)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        $dashboard->update($validated);

        return redirect()->route('dashboard.index')->with('success', 'Stat berhasil diperbarui');
    }

    public function destroy(DashboardStat $dashboard)
    {
        $dashboard->delete();

        return redirect()->route('dashboard.index')->with('success', 'Stat berhasil dihapus');
    }
}
