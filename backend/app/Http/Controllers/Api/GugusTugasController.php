<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GugusTugas;
use Illuminate\Http\Request;

class GugusTugasController extends Controller
{
    public function index(Request $request)
    {
        $query = GugusTugas::query();

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('wilayah')) {
            $query->where('wilayah', $request->wilayah);
        }

        if ($request->boolean('active_only', true)) {
            $query->where('is_active', true);
        }

        return response()->json($query->orderBy('order')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'foto_url' => 'nullable|string',
            'kategori' => 'required|in:kota,kecamatan,kelurahan',
            'wilayah' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $gugusTugas = GugusTugas::create($validated);

        return response()->json($gugusTugas, 201);
    }

    public function show(GugusTugas $gugusTuga)
    {
        return response()->json($gugusTuga);
    }

    public function update(Request $request, GugusTugas $gugusTuga)
    {
        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'jabatan' => 'sometimes|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'foto_url' => 'nullable|string',
            'kategori' => 'sometimes|in:kota,kecamatan,kelurahan',
            'wilayah' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $gugusTuga->update($validated);

        return response()->json($gugusTuga);
    }

    public function destroy(GugusTugas $gugusTuga)
    {
        $gugusTuga->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
