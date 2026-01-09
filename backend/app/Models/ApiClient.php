<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ApiClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organization',
        'api_key',
        'api_secret',
        'allowed_endpoints',
        'rate_limit',
        'contact_email',
        'contact_person',
        'description',
        'is_active',
        'last_used_at',
    ];

    protected $casts = [
        'allowed_endpoints' => 'array',
        'is_active' => 'boolean',
        'rate_limit' => 'integer',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'api_secret',
    ];

    /**
     * Generate a new API key and secret.
     */
    public static function generateCredentials(): array
    {
        $apiKey = 'bp_' . Str::random(32);
        $apiSecret = Str::random(64);

        return [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'api_secret_hashed' => Hash::make($apiSecret),
        ];
    }

    /**
     * Validate the API secret.
     */
    public function validateSecret(string $secret): bool
    {
        return Hash::check($secret, $this->api_secret);
    }

    /**
     * Check if the client can access a specific endpoint.
     */
    public function canAccessEndpoint(string $endpoint): bool
    {
        $allowedEndpoints = $this->allowed_endpoints ?? [];

        // Allow all if empty or contains wildcard
        if (empty($allowedEndpoints) || in_array('*', $allowedEndpoints)) {
            return true;
        }

        return in_array($endpoint, $allowedEndpoints);
    }

    /**
     * Update last used timestamp.
     */
    public function touchLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Scope for active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the request logs for this client.
     */
    public function requestLogs(): HasMany
    {
        return $this->hasMany(ApiRequestLog::class);
    }

    /**
     * Get the webhook endpoints for this client.
     */
    public function webhookEndpoints(): HasMany
    {
        return $this->hasMany(WebhookEndpoint::class);
    }

    /**
     * Check rate limit (requests in the last minute).
     */
    public function isRateLimited(): bool
    {
        $requestsInLastMinute = $this->requestLogs()
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        return $requestsInLastMinute >= $this->rate_limit;
    }

    /**
     * Get remaining rate limit.
     */
    public function getRemainingRateLimit(): int
    {
        $requestsInLastMinute = $this->requestLogs()
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        return max(0, $this->rate_limit - $requestsInLastMinute);
    }
}
