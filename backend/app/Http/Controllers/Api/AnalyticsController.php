<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\BundaPaud;
use App\Models\MitraPaud;
use App\Models\Complaint;
use App\Models\Gallery;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        return response()->json([
            'articles' => Article::count(),
            'bunda_paud' => BundaPaud::where('is_active', true)->count(),
            'mitra_paud' => MitraPaud::where('status', 'approved')->count(),
            'complaints' => Complaint::count(),
            'complaints_pending' => Complaint::where('status', 'pending')->count(),
            'galleries' => Gallery::count(),
            'documents' => Document::count(),
            'users' => User::where('is_active', true)->count(),
        ]);
    }

    /**
     * Get complaints trend by month
     */
    public function complaintsTrend()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->translatedFormat('M'),
                'year' => $date->year,
                'count' => Complaint::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        return response()->json($months);
    }

    /**
     * Get Bunda PAUD distribution by type
     */
    public function bundaPaudDistribution()
    {
        $distribution = BundaPaud::select('type', DB::raw('count(*) as count'))
            ->where('is_active', true)
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->type === 'kecamatan' ? 'Kecamatan' : 'Kelurahan',
                    'count' => $item->count
                ];
            });

        return response()->json($distribution);
    }

    /**
     * Get recent activity
     */
    public function recentActivity()
    {
        $activities = [];

        // Recent articles
        $articles = Article::latest()->take(3)->get()->map(function ($item) {
            return [
                'type' => 'article',
                'title' => $item->title,
                'date' => $item->created_at->diffForHumans(),
                'icon' => 'document'
            ];
        });

        // Recent complaints
        $complaints = Complaint::latest()->take(3)->get()->map(function ($item) {
            return [
                'type' => 'complaint',
                'title' => 'Pengaduan: ' . substr($item->subject ?? $item->name, 0, 30) . '...',
                'date' => $item->created_at->diffForHumans(),
                'icon' => 'chat'
            ];
        });

        // Recent mitra registrations
        $mitra = MitraPaud::latest()->take(2)->get()->map(function ($item) {
            return [
                'type' => 'mitra',
                'title' => 'Mitra baru: ' . $item->nama_organisasi,
                'date' => $item->created_at->diffForHumans(),
                'icon' => 'users'
            ];
        });

        $activities = $articles->concat($complaints)->concat($mitra)
            ->sortByDesc('date')
            ->take(8)
            ->values();

        return response()->json($activities);
    }

    /**
     * Get mitra by category
     */
    public function mitraByCategory()
    {
        $distribution = MitraPaud::select('kategori_organisasi', DB::raw('count(*) as count'))
            ->where('status', 'approved')
            ->groupBy('kategori_organisasi')
            ->get();

        return response()->json($distribution);
    }
}
