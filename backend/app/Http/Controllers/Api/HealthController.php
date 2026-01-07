<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HealthController extends Controller
{
    /**
     * Basic health check
     */
    public function ping()
    {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Detailed health check for monitoring
     */
    public function check()
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'checks' => [],
        ];

        // Database check
        try {
            DB::connection()->getPdo();
            $health['checks']['database'] = [
                'status' => 'up',
                'latency_ms' => $this->measureLatency(fn() => DB::select('SELECT 1')),
            ];
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['checks']['database'] = [
                'status' => 'down',
                'error' => $e->getMessage(),
            ];
        }

        // Cache check
        try {
            $cacheKey = 'health_check_' . time();
            Cache::put($cacheKey, 'test', 10);
            $cacheWorks = Cache::get($cacheKey) === 'test';
            Cache::forget($cacheKey);

            $health['checks']['cache'] = [
                'status' => $cacheWorks ? 'up' : 'down',
            ];
        } catch (\Exception $e) {
            $health['checks']['cache'] = [
                'status' => 'down',
                'error' => $e->getMessage(),
            ];
        }

        // Storage check
        try {
            $storagePath = storage_path('app');
            $isWritable = is_writable($storagePath);
            $health['checks']['storage'] = [
                'status' => $isWritable ? 'up' : 'down',
            ];
        } catch (\Exception $e) {
            $health['checks']['storage'] = [
                'status' => 'down',
                'error' => $e->getMessage(),
            ];
        }

        // Memory usage
        $health['metrics'] = [
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        $statusCode = $health['status'] === 'healthy' ? 200 : 503;

        return response()->json($health, $statusCode);
    }

    /**
     * Measure latency of a callback in milliseconds
     */
    private function measureLatency(callable $callback): float
    {
        $start = microtime(true);
        $callback();
        return round((microtime(true) - $start) * 1000, 2);
    }

    /**
     * Readiness probe for Kubernetes
     */
    public function ready()
    {
        try {
            DB::connection()->getPdo();
            return response()->json(['ready' => true]);
        } catch (\Exception $e) {
            return response()->json(['ready' => false], 503);
        }
    }

    /**
     * Liveness probe for Kubernetes
     */
    public function live()
    {
        return response()->json(['alive' => true]);
    }
}
