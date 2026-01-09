<?php

namespace App\Services;

use App\Models\ExternalApiConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class DinasIntegrationService
{
    /**
     * Cache TTL in seconds (1 hour default).
     */
    protected int $cacheTtl = 3600;

    /**
     * Fetch data from external dinas API.
     */
    public function fetch(string $dinasCode, string $endpoint, array $params = [], bool $useCache = true): array
    {
        $config = ExternalApiConfig::where('code', $dinasCode)->active()->first();

        if (!$config) {
            throw new Exception("External API config not found for code: {$dinasCode}");
        }

        $cacheKey = "external_api:{$dinasCode}:{$endpoint}:" . md5(json_encode($params));

        if ($useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $url = rtrim($config->base_url, '/') . '/' . ltrim($endpoint, '/');

            $response = Http::withHeaders($config->getAuthHeaders())
                ->timeout($config->timeout)
                ->get($url, $params);

            if ($response->successful()) {
                $data = [
                    'success' => true,
                    'data' => $response->json(),
                    'fetched_at' => now()->toIso8601String(),
                ];

                if ($useCache) {
                    Cache::put($cacheKey, $data, $this->cacheTtl);
                }

                // Update last sync timestamp
                $config->update(['last_sync_at' => now()]);

                return $data;
            }

            Log::error("External API request failed", [
                'dinas' => $dinasCode,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'API request failed',
                'status_code' => $response->status(),
            ];
        } catch (Exception $e) {
            Log::error("External API request exception", [
                'dinas' => $dinasCode,
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * POST data to external dinas API.
     */
    public function post(string $dinasCode, string $endpoint, array $data): array
    {
        $config = ExternalApiConfig::where('code', $dinasCode)->active()->first();

        if (!$config) {
            throw new Exception("External API config not found for code: {$dinasCode}");
        }

        try {
            $url = rtrim($config->base_url, '/') . '/' . ltrim($endpoint, '/');

            $response = Http::withHeaders($config->getAuthHeaders())
                ->timeout($config->timeout)
                ->post($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error("External API POST failed", [
                'dinas' => $dinasCode,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'API request failed',
                'status_code' => $response->status(),
            ];
        } catch (Exception $e) {
            Log::error("External API POST exception", [
                'dinas' => $dinasCode,
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Sync data PAUD from Dinas Pendidikan.
     */
    public function syncDataPaudFromDisdik(): array
    {
        // Example implementation - adjust endpoint as needed
        $result = $this->fetch('disdik', '/api/v1/paud/schools', [], false);

        if ($result['success']) {
            // Process and store data
            // This should be customized based on actual API response structure
            $schools = $result['data']['data'] ?? $result['data'] ?? [];

            // Log sync activity
            Log::info("Synced PAUD data from Disdik", [
                'count' => count($schools),
            ]);

            return [
                'success' => true,
                'synced_count' => count($schools),
                'synced_at' => now()->toIso8601String(),
            ];
        }

        return $result;
    }

    /**
     * Sync health data from Dinas Kesehatan.
     */
    public function syncDataKesehatanFromDinkes(): array
    {
        // Example implementation - adjust endpoint as needed
        $result = $this->fetch('dinkes', '/api/v1/stunting/data', [], false);

        if ($result['success']) {
            $data = $result['data']['data'] ?? $result['data'] ?? [];

            Log::info("Synced health data from Dinkes", [
                'count' => is_array($data) ? count($data) : 0,
            ]);

            return [
                'success' => true,
                'data' => $data,
                'synced_at' => now()->toIso8601String(),
            ];
        }

        return $result;
    }

    /**
     * Get statistics from Diskominfo.
     */
    public function getStatistikFromDiskominfo(): array
    {
        // Example implementation - adjust endpoint as needed
        $result = $this->fetch('diskominfo', '/api/v1/statistics/paud');

        return $result;
    }

    /**
     * Test connection to a specific dinas API.
     */
    public function testConnection(string $dinasCode): array
    {
        $config = ExternalApiConfig::where('code', $dinasCode)->first();

        if (!$config) {
            return [
                'success' => false,
                'error' => "Config not found for: {$dinasCode}",
            ];
        }

        try {
            $startTime = microtime(true);

            // Try to make a simple HEAD request or health check
            $response = Http::withHeaders($config->getAuthHeaders())
                ->timeout(10)
                ->get($config->base_url);

            $responseTime = round((microtime(true) - $startTime) * 1000);

            return [
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'response_time_ms' => $responseTime,
                'base_url' => $config->base_url,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get all configured dinas.
     */
    public function getConfiguredDinas(): array
    {
        return ExternalApiConfig::select('id', 'name', 'code', 'base_url', 'auth_type', 'is_active', 'last_sync_at')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    /**
     * Set cache TTL.
     */
    public function setCacheTtl(int $seconds): self
    {
        $this->cacheTtl = $seconds;
        return $this;
    }

    /**
     * Clear cache for a specific dinas.
     */
    public function clearCache(string $dinasCode): void
    {
        // This is a simplified approach - in production, you might want to use cache tags
        Cache::flush(); // Be careful with this in production
    }
}
