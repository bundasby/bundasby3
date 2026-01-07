<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::query();

        if ($request->has('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->has('periode')) {
            $query->where('periode', $request->periode);
        }

        if ($request->boolean('active_only', true)) {
            $query->where('is_active', true);
        }

        return response()->json($query->orderBy('tahun', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'required|string',
            'file_type' => 'nullable|string|max:50',
            'periode' => 'nullable|string|max:100',
            'tahun' => 'nullable|integer|min:1900|max:2100',
            'is_active' => 'boolean',
        ]);

        $laporan = Laporan::create($validated);

        return response()->json($laporan, 201);
    }

    public function show(Laporan $laporan)
    {
        return response()->json($laporan);
    }

    public function update(Request $request, Laporan $laporan)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'sometimes|string',
            'file_type' => 'nullable|string|max:50',
            'periode' => 'nullable|string|max:100',
            'tahun' => 'nullable|integer|min:1900|max:2100',
            'is_active' => 'boolean',
        ]);

        $laporan->update($validated);

        return response()->json($laporan);
    }

    public function destroy(Laporan $laporan)
    {
        $laporan->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
