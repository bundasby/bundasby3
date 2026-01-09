<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use App\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WebhookController extends Controller
{
    protected WebhookService $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    /**
     * Display a listing of webhook endpoints.
     */
    public function index(): JsonResponse
    {
        $webhooks = WebhookEndpoint::with('apiClient:id,name,organization')
            ->withCount('logs')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($webhook) {
                $successLogs = $webhook->logs()->successful()->count();
                $failedLogs = $webhook->logs()->where('status', 'failed')->count();

                return [
                    'id' => $webhook->id,
                    'name' => $webhook->name,
                    'url' => $webhook->url,
                    'events' => $webhook->events,
                    'api_client' => $webhook->apiClient,
                    'is_active' => $webhook->is_active,
                    'retry_count' => $webhook->retry_count,
                    'timeout' => $webhook->timeout,
                    'description' => $webhook->description,
                    'logs_count' => $webhook->logs_count,
                    'success_rate' => $webhook->logs_count > 0
                        ? round(($successLogs / $webhook->logs_count) * 100, 2)
                        : 0,
                    'created_at' => $webhook->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $webhooks,
        ]);
    }

    /**
     * Store a newly created webhook endpoint.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'api_client_id' => 'required|exists:api_clients,id',
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array|min:1',
            'events.*' => 'string',
            'is_active' => 'nullable|boolean',
            'retry_count' => 'nullable|integer|min:0|max:10',
            'timeout' => 'nullable|integer|min:5|max:120',
            'description' => 'nullable|string',
        ]);

        $webhook = WebhookEndpoint::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Webhook endpoint created successfully',
            'data' => [
                'id' => $webhook->id,
                'name' => $webhook->name,
                'url' => $webhook->url,
                'events' => $webhook->events,
                'secret' => $webhook->secret, // Show once for the user to save
            ],
        ], 201);
    }

    /**
     * Display the specified webhook endpoint.
     */
    public function show(WebhookEndpoint $webhook): JsonResponse
    {
        $webhook->load('apiClient:id,name,organization');

        $recentLogs = $webhook->logs()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $webhook->id,
                'name' => $webhook->name,
                'url' => $webhook->url,
                'events' => $webhook->events,
                'api_client' => $webhook->apiClient,
                'is_active' => $webhook->is_active,
                'retry_count' => $webhook->retry_count,
                'timeout' => $webhook->timeout,
                'description' => $webhook->description,
                'created_at' => $webhook->created_at,
                'updated_at' => $webhook->updated_at,
                'recent_logs' => $recentLogs,
            ],
        ]);
    }

    /**
     * Update the specified webhook endpoint.
     */
    public function update(Request $request, WebhookEndpoint $webhook): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'url' => 'sometimes|required|url',
            'events' => 'sometimes|required|array|min:1',
            'events.*' => 'string',
            'is_active' => 'nullable|boolean',
            'retry_count' => 'nullable|integer|min:0|max:10',
            'timeout' => 'nullable|integer|min:5|max:120',
            'description' => 'nullable|string',
        ]);

        $webhook->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Webhook endpoint updated successfully',
        ]);
    }

    /**
     * Remove the specified webhook endpoint.
     */
    public function destroy(WebhookEndpoint $webhook): JsonResponse
    {
        $webhook->delete();

        return response()->json([
            'success' => true,
            'message' => 'Webhook endpoint deleted successfully',
        ]);
    }

    /**
     * Send a test webhook.
     */
    public function test(WebhookEndpoint $webhook): JsonResponse
    {
        $result = $this->webhookService->sendTest($webhook);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success']
                ? 'Test webhook sent successfully'
                : 'Test webhook failed',
            'data' => $result,
        ]);
    }

    /**
     * Get logs for a webhook endpoint.
     */
    public function logs(Request $request, WebhookEndpoint $webhook): JsonResponse
    {
        $perPage = $request->input('per_page', 50);
        $status = $request->input('status');

        $query = $webhook->logs()
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $logs = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }

    /**
     * Get available webhook events.
     */
    public function events(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->webhookService->getAvailableEvents(),
        ]);
    }

    /**
     * Get webhook statistics.
     */
    public function statistics(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->webhookService->getStatistics(),
        ]);
    }

    /**
     * Retry failed webhooks.
     */
    public function retryFailed(): JsonResponse
    {
        $result = $this->webhookService->retryFailed();

        return response()->json([
            'success' => true,
            'message' => "Retried {$result['retried_count']} failed webhooks",
            'data' => $result,
        ]);
    }

    /**
     * Regenerate webhook secret.
     */
    public function regenerateSecret(WebhookEndpoint $webhook): JsonResponse
    {
        $newSecret = \Illuminate\Support\Str::random(64);
        $webhook->update(['secret' => $newSecret]);

        return response()->json([
            'success' => true,
            'message' => 'Webhook secret regenerated. Please update your webhook receiver.',
            'data' => [
                'secret' => $newSecret, // Show once
            ],
        ]);
    }
}
