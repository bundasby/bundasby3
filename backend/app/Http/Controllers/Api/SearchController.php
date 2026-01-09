<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Document;
use App\Models\Gallery;
use App\Models\BundaPaud;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Global search across all content
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
            'type' => 'nullable|string|in:all,articles,documents,galleries,bunda_paud,profiles',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        $query = trim($request->q);
        $type = $request->get('type', 'all');
        $limit = $request->get('limit', 10);

        $results = [];

        // Search Articles
        if (in_array($type, ['all', 'articles'])) {
            $results['articles'] = $this->searchArticles($query, $limit);
        }

        // Search Documents
        if (in_array($type, ['all', 'documents'])) {
            $results['documents'] = $this->searchDocuments($query, $limit);
        }

        // Search Galleries
        if (in_array($type, ['all', 'galleries'])) {
            $results['galleries'] = $this->searchGalleries($query, $limit);
        }

        // Search Bunda PAUD
        if (in_array($type, ['all', 'bunda_paud'])) {
            $results['bunda_paud'] = $this->searchBundaPaud($query, $limit);
        }

        // Search Profiles
        if (in_array($type, ['all', 'profiles'])) {
            $results['profiles'] = $this->searchProfiles($query, $limit);
        }

        // Calculate totals
        $totalResults = 0;
        foreach ($results as $category) {
            $totalResults += count($category);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'query' => $query,
                'total' => $totalResults,
                'results' => $results
            ]
        ]);
    }

    /**
     * Search suggestions (autocomplete)
     */
    public function suggest(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:50'
        ]);

        $query = trim($request->q);
        $suggestions = [];

        // Get article titles
        $articles = Article::published()
            ->where('title', 'ILIKE', "%{$query}%")
            ->limit(5)
            ->pluck('title')
            ->toArray();

        // Get document titles
        $documents = Document::where('title', 'ILIKE', "%{$query}%")
            ->limit(3)
            ->pluck('title')
            ->toArray();

        // Get Bunda PAUD names
        $bundaPaud = BundaPaud::where('name', 'ILIKE', "%{$query}%")
            ->limit(3)
            ->pluck('name')
            ->toArray();

        $suggestions = array_merge($articles, $documents, $bundaPaud);
        $suggestions = array_unique($suggestions);
        $suggestions = array_slice($suggestions, 0, 10);

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }

    /**
     * Full-text search in articles
     */
    public function fullTextSearch(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:3|max:200'
        ]);

        $query = trim($request->q);

        // PostgreSQL full-text search
        $results = Article::published()
            ->select([
                'id',
                'title',
                'slug',
                'excerpt',
                'category',
                'thumbnail',
                'published_at',
                DB::raw("ts_rank(to_tsvector('indonesian', title || ' ' || content), plainto_tsquery('indonesian', ?)) as rank")
            ])
            ->whereRaw("to_tsvector('indonesian', title || ' ' || content) @@ plainto_tsquery('indonesian', ?)", [$query, $query])
            ->orderByDesc('rank')
            ->limit(20)
            ->get();

        // Highlight matched text
        $results = $results->map(function ($article) use ($query) {
            $article->highlighted_excerpt = $this->highlightMatches($article->excerpt, $query);
            return $article;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'query' => $query,
                'count' => $results->count(),
                'results' => $results
            ]
        ]);
    }

    /**
     * Search articles
     */
    private function searchArticles(string $query, int $limit): array
    {
        return Article::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'ILIKE', "%{$query}%")
                    ->orWhere('excerpt', 'ILIKE', "%{$query}%")
                    ->orWhere('content', 'ILIKE', "%{$query}%");
            })
            ->select(['id', 'title', 'slug', 'excerpt', 'category', 'thumbnail', 'published_at'])
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get()
            ->map(function ($article) use ($query) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'excerpt' => $this->truncateAndHighlight($article->excerpt, $query),
                    'category' => $article->category,
                    'thumbnail' => $article->thumbnail,
                    'url' => "/berita/{$article->slug}",
                    'type' => 'article',
                    'date' => $article->published_at?->format('d M Y')
                ];
            })
            ->toArray();
    }

    /**
     * Search documents
     */
    private function searchDocuments(string $query, int $limit): array
    {
        return Document::where(function ($q) use ($query) {
            $q->where('title', 'ILIKE', "%{$query}%")
                ->orWhere('description', 'ILIKE', "%{$query}%");
        })
            ->select(['id', 'title', 'description', 'category', 'file_url'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($doc) use ($query) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'excerpt' => $this->truncateAndHighlight($doc->description, $query),
                    'category' => $doc->category,
                    'file_url' => $doc->file_url,
                    'url' => "/dokumen",
                    'type' => 'document'
                ];
            })
            ->toArray();
    }

    /**
     * Search galleries
     */
    private function searchGalleries(string $query, int $limit): array
    {
        return Gallery::active()
            ->where(function ($q) use ($query) {
                $q->where('title', 'ILIKE', "%{$query}%")
                    ->orWhere('description', 'ILIKE', "%{$query}%");
            })
            ->select(['id', 'title', 'description', 'image_url', 'category'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($gallery) use ($query) {
                return [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'excerpt' => $this->truncateAndHighlight($gallery->description, $query),
                    'image' => $gallery->image_url,
                    'category' => $gallery->category,
                    'url' => "/galeri",
                    'type' => 'gallery'
                ];
            })
            ->toArray();
    }

    /**
     * Search Bunda PAUD
     */
    private function searchBundaPaud(string $query, int $limit): array
    {
        return BundaPaud::where(function ($q) use ($query) {
            $q->where('name', 'ILIKE', "%{$query}%")
                ->orWhere('kecamatan', 'ILIKE', "%{$query}%")
                ->orWhere('kelurahan', 'ILIKE', "%{$query}%")
                ->orWhere('position', 'ILIKE', "%{$query}%");
        })
            ->select(['id', 'name', 'position', 'kecamatan', 'kelurahan', 'photo'])
            ->limit($limit)
            ->get()
            ->map(function ($bunda) {
                return [
                    'id' => $bunda->id,
                    'title' => $bunda->name,
                    'excerpt' => "{$bunda->position} - {$bunda->kecamatan}",
                    'image' => $bunda->photo,
                    'url' => "/bunda-paud",
                    'type' => 'bunda_paud'
                ];
            })
            ->toArray();
    }

    /**
     * Search profiles
     */
    private function searchProfiles(string $query, int $limit): array
    {
        return Profile::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'ILIKE', "%{$query}%")
                    ->orWhere('content', 'ILIKE', "%{$query}%");
            })
            ->select(['id', 'section_key', 'title', 'content'])
            ->limit($limit)
            ->get()
            ->map(function ($profile) use ($query) {
                return [
                    'id' => $profile->id,
                    'title' => $profile->title,
                    'excerpt' => $this->truncateAndHighlight(strip_tags($profile->content), $query),
                    'url' => "/profil/{$profile->section_key}",
                    'type' => 'profile'
                ];
            })
            ->toArray();
    }

    /**
     * Truncate text and highlight matches
     */
    private function truncateAndHighlight(?string $text, string $query, int $length = 150): string
    {
        if (!$text)
            return '';

        $text = strip_tags($text);

        // Find position of query in text
        $pos = stripos($text, $query);

        if ($pos !== false) {
            // Center the excerpt around the match
            $start = max(0, $pos - 50);
            $text = ($start > 0 ? '...' : '') . substr($text, $start, $length);
        } else {
            $text = substr($text, 0, $length);
        }

        if (strlen($text) >= $length) {
            $text .= '...';
        }

        return $this->highlightMatches($text, $query);
    }

    /**
     * Highlight matching text
     */
    private function highlightMatches(string $text, string $query): string
    {
        return preg_replace(
            '/(' . preg_quote($query, '/') . ')/i',
            '<mark>$1</mark>',
            $text
        );
    }
}
