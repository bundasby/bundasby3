<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Majalah;
use Illuminate\Http\Request;

class MajalahController extends Controller
{
    public function index(Request $request)
    {
        $query = Majalah::query();

        if ($request->boolean('active_only', true)) {
            $query->where('is_active', true);
        }

        return response()->json($query->orderBy('publish_date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_url' => 'nullable|string',
            'file_url' => 'required|string',
            'edition' => 'nullable|string|max:100',
            'publish_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $majalah = Majalah::create($validated);

        return response()->json($majalah, 201);
    }

    public function show(Majalah $majalah)
    {
        return response()->json($majalah);
    }

    public function update(Request $request, Majalah $majalah)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'cover_url' => 'nullable|string',
            'file_url' => 'sometimes|string',
            'edition' => 'nullable|string|max:100',
            'publish_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $majalah->update($validated);

        return response()->json($majalah);
    }

    public function destroy(Majalah $majalah)
    {
        $majalah->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
