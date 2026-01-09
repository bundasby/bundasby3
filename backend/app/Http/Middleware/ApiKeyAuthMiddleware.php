<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');

        // Check if API key is provided
        if (!$apiKey || !$apiSecret) {
            return $this->errorResponse(
                'API key and secret are required. Please provide X-API-Key and X-API-Secret headers.',
                401
            );
        }

        // Find the API client
        $client = ApiClient::where('api_key', $apiKey)->first();

        if (!$client) {
            return $this->errorResponse('Invalid API key.', 401);
        }

        // Validate secret
        if (!$client->validateSecret($apiSecret)) {
            return $this->errorResponse('Invalid API secret.', 401);
        }

        // Check if client is active
        if (!$client->is_active) {
            return $this->errorResponse('API client is inactive. Please contact administrator.', 403);
        }

        // Check rate limit
        if ($client->isRateLimited()) {
            $this->logRequest($client, $request, 429, $startTime);
            return $this->errorResponse(
                'Rate limit exceeded. Maximum ' . $client->rate_limit . ' requests per minute.',
                429,
                [
                    'rate_limit' => $client->rate_limit,
                    'remaining' => 0,
                    'retry_after' => 60,
                ]
            );
        }

        // Check endpoint access
        $endpoint = $this->getEndpointFromPath($request->path());
        if (!$client->canAccessEndpoint($endpoint)) {
            $this->logRequest($client, $request, 403, $startTime);
            return $this->errorResponse(
                'You do not have access to this endpoint.',
                403,
                ['requested_endpoint' => $endpoint]
            );
        }

        // Attach client to request for use in controllers
        $request->attributes->set('api_client', $client);

        // Update last used timestamp
        $client->touchLastUsed();

        // Process the request
        $response = $next($request);

        // Log the request
        $this->logRequest($client, $request, $response->getStatusCode(), $startTime);

        // Add rate limit headers
        $remaining = $client->getRemainingRateLimit();
        $response->headers->set('X-RateLimit-Limit', $client->rate_limit);
        $response->headers->set('X-RateLimit-Remaining', max(0, $remaining - 1));
        $response->headers->set('X-RateLimit-Reset', now()->addMinute()->timestamp);

        return $response;
    }

    /**
     * Log the API request.
     */
    protected function logRequest(ApiClient $client, Request $request, int $statusCode, float $startTime): void
    {
        $responseTime = round((microtime(true) - $startTime) * 1000);

        ApiRequestLog::create([
            'api_client_id' => $client->id,
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'response_code' => $statusCode,
            'response_time_ms' => $responseTime,
            'request_params' => $request->query(),
            'created_at' => now(),
        ]);
    }

    /**
     * Get endpoint identifier from path.
     */
    protected function getEndpointFromPath(string $path): string
    {
        // Remove api/public/v1/ prefix and get first segment
        $path = preg_replace('#^api/public/v\d+/#', '', $path);
        $segments = explode('/', $path);
        return $segments[0] ?? $path;
    }

    /**
     * Return error response.
     */
    protected function errorResponse(string $message, int $statusCode, array $extra = []): Response
    {
        $data = array_merge([
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode,
        ], $extra);

        return response()->json($data, $statusCode);
    }
}
