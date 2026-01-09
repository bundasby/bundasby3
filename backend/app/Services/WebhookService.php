<?php

namespace App\Services;

use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class WebhookService
{
    /**
     * Dispatch a webhook event to all subscribed endpoints.
     */
    public function dispatch(string $event, array $data): array
    {
        $endpoints = WebhookEndpoint::active()
            ->forEvent($event)
            ->with('apiClient')
            ->get();

        $results = [];

        foreach ($endpoints as $endpoint) {
            // Check if API client is active
            if (!$endpoint->apiClient || !$endpoint->apiClient->is_active) {
                continue;
            }

            $log = $this->createLog($endpoint, $event, $data);
            $result = $this->sendWebhook($endpoint, $event, $data, $log);
            $results[] = $result;
        }

        return [
            'event' => $event,
            'endpoints_notified' => count($results),
            'results' => $results,
        ];
    }

    /**
     * Send webhook to a specific endpoint.
     */
    protected function sendWebhook(WebhookEndpoint $endpoint, string $event, array $data, WebhookLog $log): array
    {
        $payload = $this->buildPayload($event, $data);
        $signature = $endpoint->signPayload($payload);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Webhook-Event' => $event,
                'X-Webhook-Signature' => $signature,
                'X-Webhook-Timestamp' => now()->timestamp,
                'User-Agent' => 'BundaPAUD-Webhook/1.0',
            ])
                ->timeout($endpoint->timeout)
                ->post($endpoint->url, $payload);

            if ($response->successful()) {
                $log->markAsSuccess($response->status(), $response->body());

                return [
                    'endpoint_id' => $endpoint->id,
                    'success' => true,
                    'status_code' => $response->status(),
                ];
            }

            $log->markAsFailed($response->status(), 'HTTP request failed', $response->body());

            return [
                'endpoint_id' => $endpoint->id,
                'success' => false,
                'status_code' => $response->status(),
                'error' => 'HTTP request failed',
            ];
        } catch (Exception $e) {
            Log::error("Webhook delivery failed", [
                'endpoint_id' => $endpoint->id,
                'event' => $event,
                'error' => $e->getMessage(),
            ]);

            $log->markAsFailed(0, $e->getMessage());

            return [
                'endpoint_id' => $endpoint->id,
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build webhook payload.
     */
    protected function buildPayload(string $event, array $data): array
    {
        return [
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
            'source' => [
                'name' => 'Bunda PAUD Kota Surabaya',
                'url' => config('app.url'),
            ],
        ];
    }

    /**
     * Create a webhook log entry.
     */
    protected function createLog(WebhookEndpoint $endpoint, string $event, array $data): WebhookLog
    {
        return WebhookLog::create([
            'webhook_endpoint_id' => $endpoint->id,
            'event' => $event,
            'payload' => $this->buildPayload($event, $data),
            'status' => WebhookLog::STATUS_PENDING,
            'attempt' => 1,
        ]);
    }

    /**
     * Retry failed webhooks.
     */
    public function retryFailed(): array
    {
        $failedLogs = WebhookLog::where('status', WebhookLog::STATUS_FAILED)
            ->whereHas('webhookEndpoint', function ($q) {
                $q->where('is_active', true);
            })
            ->with('webhookEndpoint')
            ->get()
            ->filter(function ($log) {
                return $log->attempt < $log->webhookEndpoint->retry_count;
            });

        $results = [];

        foreach ($failedLogs as $log) {
            $log->incrementAttempt();

            $result = $this->sendWebhook(
                $log->webhookEndpoint,
                $log->event,
                $log->payload['data'] ?? [],
                $log
            );

            $results[] = [
                'log_id' => $log->id,
                'attempt' => $log->attempt,
                'result' => $result,
            ];
        }

        return [
            'retried_count' => count($results),
            'results' => $results,
        ];
    }

    /**
     * Send a test webhook to an endpoint.
     */
    public function sendTest(WebhookEndpoint $endpoint): array
    {
        $testData = [
            'message' => 'This is a test webhook from Bunda PAUD Kota Surabaya',
            'test' => true,
            'timestamp' => now()->toIso8601String(),
        ];

        $log = $this->createLog($endpoint, 'test.ping', $testData);

        return $this->sendWebhook($endpoint, 'test.ping', $testData, $log);
    }

    /**
     * Get available webhook events.
     */
    public function getAvailableEvents(): array
    {
        return WebhookEndpoint::EVENTS;
    }

    /**
     * Dispatch specific events.
     */
    public function dispatchArticleCreated(array $article): array
    {
        return $this->dispatch('article.created', $article);
    }

    public function dispatchArticleUpdated(array $article): array
    {
        return $this->dispatch('article.updated', $article);
    }

    public function dispatchDataPaudCreated(array $data): array
    {
        return $this->dispatch('data_paud.created', $data);
    }

    public function dispatchDataPaudUpdated(array $data): array
    {
        return $this->dispatch('data_paud.updated', $data);
    }

    public function dispatchBundaPaudUpdated(array $data): array
    {
        return $this->dispatch('bunda_paud.updated', $data);
    }

    public function dispatchMitraPaudRegistered(array $mitra): array
    {
        return $this->dispatch('mitra_paud.registered', $mitra);
    }

    public function dispatchMitraPaudApproved(array $mitra): array
    {
        return $this->dispatch('mitra_paud.approved', $mitra);
    }

    public function dispatchComplaintNew(array $complaint): array
    {
        return $this->dispatch('complaint.new', $complaint);
    }

    public function dispatchComplaintResponded(array $complaint): array
    {
        return $this->dispatch('complaint.responded', $complaint);
    }

    public function dispatchStatisticsUpdated(array $stats): array
    {
        return $this->dispatch('statistics.updated', $stats);
    }

    /**
     * Get webhook statistics.
     */
    public function getStatistics(): array
    {
        $totalEndpoints = WebhookEndpoint::count();
        $activeEndpoints = WebhookEndpoint::active()->count();

        $totalLogs = WebhookLog::count();
        $successfulDeliveries = WebhookLog::successful()->count();
        $failedDeliveries = WebhookLog::where('status', WebhookLog::STATUS_FAILED)->count();
        $pendingDeliveries = WebhookLog::pending()->count();

        return [
            'endpoints' => [
                'total' => $totalEndpoints,
                'active' => $activeEndpoints,
            ],
            'deliveries' => [
                'total' => $totalLogs,
                'successful' => $successfulDeliveries,
                'failed' => $failedDeliveries,
                'pending' => $pendingDeliveries,
                'success_rate' => $totalLogs > 0
                    ? round(($successfulDeliveries / $totalLogs) * 100, 2)
                    : 0,
            ],
        ];
    }
}
