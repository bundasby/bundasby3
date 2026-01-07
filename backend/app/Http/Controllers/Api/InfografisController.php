<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Infografis;
use Illuminate\Http\Request;

class InfografisController extends Controller
{
    public function index(Request $request)
    {
        $query = Infografis::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->boolean('active_only', true)) {
            $query->where('is_active', true);
        }

        return response()->json($query->orderBy('order')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $infografis = Infografis::create($validated);

        return response()->json($infografis, 201);
    }

    public function show(Infografis $infografi)
    {
        return response()->json($infografi);
    }

    public function update(Request $request, Infografis $infografi)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'sometimes|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $infografi->update($validated);

        return response()->json($infografi);
    }

    public function destroy(Infografis $infografi)
    {
        $infografi->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
