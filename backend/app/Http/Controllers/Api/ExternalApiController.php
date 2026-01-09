<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExternalApiConfig;
use App\Services\DinasIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ExternalApiController extends Controller
{
    protected DinasIntegrationService $integrationService;

    public function __construct(DinasIntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    /**
     * Display a listing of external API configs.
     */
    public function index(): JsonResponse
    {
        $configs = ExternalApiConfig::orderBy('name')
            ->get()
            ->map(function ($config) {
                return [
                    'id' => $config->id,
                    'name' => $config->name,
                    'code' => $config->code,
                    'base_url' => $config->base_url,
                    'auth_type' => $config->auth_type,
                    'timeout' => $config->timeout,
                    'description' => $config->description,
                    'is_active' => $config->is_active,
                    'last_sync_at' => $config->last_sync_at,
                    'created_at' => $config->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $configs,
        ]);
    }

    /**
     * Store a newly created external API config.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:external_api_configs,code',
            'base_url' => 'required|url',
            'headers' => 'nullable|array',
            'auth_type' => ['required', Rule::in(['none', 'api_key', 'bearer', 'basic'])],
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'timeout' => 'nullable|integer|min:5|max:120',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $config = ExternalApiConfig::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'External API config created successfully',
            'data' => [
                'id' => $config->id,
                'name' => $config->name,
                'code' => $config->code,
                'base_url' => $config->base_url,
                'auth_type' => $config->auth_type,
                'is_active' => $config->is_active,
            ],
        ], 201);
    }

    /**
     * Display the specified external API config.
     */
    public function show(ExternalApiConfig $externalApi): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $externalApi->id,
                'name' => $externalApi->name,
                'code' => $externalApi->code,
                'base_url' => $externalApi->base_url,
                'headers' => $externalApi->headers,
                'auth_type' => $externalApi->auth_type,
                'timeout' => $externalApi->timeout,
                'description' => $externalApi->description,
                'is_active' => $externalApi->is_active,
                'last_sync_at' => $externalApi->last_sync_at,
                'created_at' => $externalApi->created_at,
                'updated_at' => $externalApi->updated_at,
            ],
        ]);
    }

    /**
     * Update the specified external API config.
     */
    public function update(Request $request, ExternalApiConfig $externalApi): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('external_api_configs')->ignore($externalApi->id)],
            'base_url' => 'sometimes|required|url',
            'headers' => 'nullable|array',
            'auth_type' => ['sometimes', 'required', Rule::in(['none', 'api_key', 'bearer', 'basic'])],
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'timeout' => 'nullable|integer|min:5|max:120',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $externalApi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'External API config updated successfully',
        ]);
    }

    /**
     * Remove the specified external API config.
     */
    public function destroy(ExternalApiConfig $externalApi): JsonResponse
    {
        $externalApi->delete();

        return response()->json([
            'success' => true,
            'message' => 'External API config deleted successfully',
        ]);
    }

    /**
     * Test connection to an external API.
     */
    public function test(ExternalApiConfig $externalApi): JsonResponse
    {
        $result = $this->integrationService->testConnection($externalApi->code);

        return response()->json([
            'success' => $result['success'],
            'data' => $result,
        ]);
    }

    /**
     * Sync data from an external API.
     */
    public function sync(Request $request, ExternalApiConfig $externalApi): JsonResponse
    {
        $endpoint = $request->input('endpoint', '/');
        $params = $request->input('params', []);

        $result = $this->integrationService->fetch($externalApi->code, $endpoint, $params, false);

        return response()->json([
            'success' => $result['success'],
            'data' => $result,
        ]);
    }

    /**
     * Fetch data from external API.
     */
    public function fetch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dinas_code' => 'required|string|exists:external_api_configs,code',
            'endpoint' => 'required|string',
            'params' => 'nullable|array',
            'use_cache' => 'nullable|boolean',
        ]);

        $result = $this->integrationService->fetch(
            $validated['dinas_code'],
            $validated['endpoint'],
            $validated['params'] ?? [],
            $validated['use_cache'] ?? true
        );

        return response()->json($result);
    }
}
