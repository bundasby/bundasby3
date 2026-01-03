<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Get all published articles
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::published()
            ->with('author:id,name')
            ->byCategory($request->category)
            ->orderBy('published_at', 'desc');

        $articles = $request->has('limit')
            ? $query->limit($request->limit)->get()
            : $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $articles,
        ]);
    }

    /**
     * Get single article by slug
     */
    public function show(string $slug): JsonResponse
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with('author:id,name')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $article,
        ]);
    }

    /**
     * Store a new article (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles,slug',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'thumbnail' => 'nullable|string',
            'category' => 'required|string|in:kegiatan,edukasi,pengumuman',
            'is_published' => 'boolean',
        ]);

        $validated['author_id'] = $request->user()->id;
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        $article = Article::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article created successfully',
            'data' => $article,
        ], 201);
    }

    /**
     * Update an article (Admin only)
     */
    public function update(Request $request, Article $article): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:articles,slug,' . $article->id,
            'excerpt' => 'sometimes|string|max:500',
            'content' => 'sometimes|string',
            'thumbnail' => 'nullable|string',
            'category' => 'sometimes|string|in:kegiatan,edukasi,pengumuman',
            'is_published' => 'boolean',
        ]);

        if (isset($validated['is_published']) && $validated['is_published'] && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Article updated successfully',
            'data' => $article,
        ]);
    }

    /**
     * Delete an article (Admin only)
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully',
        ]);
    }
}
