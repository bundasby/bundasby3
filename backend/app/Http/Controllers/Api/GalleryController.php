<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GalleryController extends Controller
{
    /**
     * Get all active gallery items
     */
    public function index(Request $request): JsonResponse
    {
        $galleries = Gallery::active()
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by category if requested
        if ($request->grouped) {
            $galleries = $galleries->groupBy('category');
        }

        return response()->json([
            'success' => true,
            'data' => $galleries,
        ]);
    }

    /**
     * Store a new gallery item (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'required|string',
            'category' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $gallery = Gallery::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Gallery item created successfully',
            'data' => $gallery,
        ], 201);
    }

    /**
     * Update a gallery item (Admin only)
     */
    public function update(Request $request, Gallery $gallery): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'sometimes|string',
            'category' => 'sometimes|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $gallery->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Gallery item updated successfully',
            'data' => $gallery,
        ]);
    }

    /**
     * Delete a gallery item (Admin only)
     */
    public function destroy(Gallery $gallery): JsonResponse
    {
        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gallery item deleted successfully',
        ]);
    }
}
