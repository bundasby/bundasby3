<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ApiClientController extends Controller
{
    /**
     * Display a listing of API clients.
     */
    public function index(): JsonResponse
    {
        $clients = ApiClient::orderBy('name')
            ->withCount('requestLogs')
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'organization' => $client->organization,
                    'api_key' => $client->api_key,
                    'allowed_endpoints' => $client->allowed_endpoints,
                    'rate_limit' => $client->rate_limit,
                    'contact_email' => $client->contact_email,
                    'contact_person' => $client->contact_person,
                    'description' => $client->description,
                    'is_active' => $client->is_active,
                    'last_used_at' => $client->last_used_at,
                    'request_logs_count' => $client->request_logs_count,
                    'created_at' => $client->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $clients,
        ]);
    }

    /**
     * Store a newly created API client.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'allowed_endpoints' => 'nullable|array',
            'allowed_endpoints.*' => 'string',
            'rate_limit' => 'nullable|integer|min:10|max:10000',
            'contact_email' => 'required|email',
            'contact_person' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Generate API credentials
        $credentials = ApiClient::generateCredentials();

        $client = ApiClient::create([
            'name' => $validated['name'],
            'organization' => $validated['organization'],
            'api_key' => $credentials['api_key'],
            'api_secret' => $credentials['api_secret_hashed'],
            'allowed_endpoints' => $validated['allowed_endpoints'] ?? ['*'],
            'rate_limit' => $validated['rate_limit'] ?? 100,
            'contact_email' => $validated['contact_email'],
            'contact_person' => $validated['contact_person'],
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'API client created successfully. Please save the API secret, it will not be shown again.',
            'data' => [
                'id' => $client->id,
                'name' => $client->name,
                'organization' => $client->organization,
                'api_key' => $credentials['api_key'],
                'api_secret' => $credentials['api_secret'], // Only shown once!
                'allowed_endpoints' => $client->allowed_endpoints,
                'rate_limit' => $client->rate_limit,
            ],
        ], 201);
    }

    /**
     * Display the specified API client.
     */
    public function show(ApiClient $apiClient): JsonResponse
    {
        $apiClient->loadCount('requestLogs');

        // Get recent request statistics
        $last24Hours = ApiRequestLog::where('api_client_id', $apiClient->id)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        $successRate = ApiRequestLog::where('api_client_id', $apiClient->id)
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN response_code >= 200 AND response_code < 300 THEN 1 ELSE 0 END) as successful')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $apiClient->id,
                'name' => $apiClient->name,
                'organization' => $apiClient->organization,
                'api_key' => $apiClient->api_key,
                'allowed_endpoints' => $apiClient->allowed_endpoints,
                'rate_limit' => $apiClient->rate_limit,
                'contact_email' => $apiClient->contact_email,
                'contact_person' => $apiClient->contact_person,
                'description' => $apiClient->description,
                'is_active' => $apiClient->is_active,
                'last_used_at' => $apiClient->last_used_at,
                'request_logs_count' => $apiClient->request_logs_count,
                'statistics' => [
                    'requests_last_24h' => $last24Hours,
                    'success_rate' => $successRate->total > 0
                        ? round(($successRate->successful / $successRate->total) * 100, 2)
                        : 0,
                ],
                'created_at' => $apiClient->created_at,
                'updated_at' => $apiClient->updated_at,
            ],
        ]);
    }

    /**
     * Update the specified API client.
     */
    public function update(Request $request, ApiClient $apiClient): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'organization' => 'sometimes|required|string|max:255',
            'allowed_endpoints' => 'nullable|array',
            'allowed_endpoints.*' => 'string',
            'rate_limit' => 'nullable|integer|min:10|max:10000',
            'contact_email' => 'sometimes|required|email',
            'contact_person' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $apiClient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'API client updated successfully',
        ]);
    }

    /**
     * Remove the specified API client.
     */
    public function destroy(ApiClient $apiClient): JsonResponse
    {
        $apiClient->delete();

        return response()->json([
            'success' => true,
            'message' => 'API client deleted successfully',
        ]);
    }

    /**
     * Regenerate API key and secret.
     */
    public function regenerateKey(ApiClient $apiClient): JsonResponse
    {
        $credentials = ApiClient::generateCredentials();

        $apiClient->update([
            'api_key' => $credentials['api_key'],
            'api_secret' => $credentials['api_secret_hashed'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'API credentials regenerated. Please save the new API secret, it will not be shown again.',
            'data' => [
                'api_key' => $credentials['api_key'],
                'api_secret' => $credentials['api_secret'], // Only shown once!
            ],
        ]);
    }

    /**
     * Get request logs for an API client.
     */
    public function logs(Request $request, ApiClient $apiClient): JsonResponse
    {
        $perPage = $request->input('per_page', 50);
        $method = $request->input('method');
        $status = $request->input('status');

        $query = $apiClient->requestLogs()
            ->orderBy('created_at', 'desc');

        if ($method) {
            $query->where('method', strtoupper($method));
        }

        if ($status === 'success') {
            $query->successful();
        } elseif ($status === 'failed') {
            $query->failed();
        }

        $logs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * Get list of available endpoints.
     */
    public function availableEndpoints(): JsonResponse
    {
        $endpoints = [
            '*' => 'All endpoints',
            'data-paud' => 'Data PAUD',
            'bunda-paud' => 'Data Bunda PAUD',
            'mitra-paud' => 'Data Mitra PAUD',
            'statistics' => 'Statistics',
            'articles' => 'Articles/News',
            'programs' => 'Programs',
        ];

        return response()->json([
            'success' => true,
            'data' => $endpoints,
        ]);
    }
}
