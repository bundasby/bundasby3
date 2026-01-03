<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    /**
     * Get all active documents
     */
    public function index(Request $request): JsonResponse
    {
        $documents = Document::active()
            ->byCategory($request->category)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents,
        ]);
    }

    /**
     * Store a new document (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'required|string',
            'file_type' => 'required|string',
            'file_size' => 'required|integer',
            'category' => 'required|string|in:kegiatan,regulasi,anggaran,monitoring',
            'is_active' => 'boolean',
        ]);

        $document = Document::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Document created successfully',
            'data' => $document,
        ], 201);
    }

    /**
     * Update a document (Admin only)
     */
    public function update(Request $request, Document $document): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'sometimes|string',
            'file_type' => 'sometimes|string',
            'file_size' => 'sometimes|integer',
            'category' => 'sometimes|string|in:kegiatan,regulasi,anggaran,monitoring',
            'is_active' => 'boolean',
        ]);

        $document->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'data' => $document,
        ]);
    }

    /**
     * Delete a document (Admin only)
     */
    public function destroy(Document $document): JsonResponse
    {
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully',
        ]);
    }
}
