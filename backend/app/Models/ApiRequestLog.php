<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRequestLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'api_client_id',
        'endpoint',
        'method',
        'ip_address',
        'response_code',
        'response_time_ms',
        'request_params',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'request_params' => 'array',
        'response_code' => 'integer',
        'response_time_ms' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the API client that owns this log.
     */
    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }

    /**
     * Scope for successful requests.
     */
    public function scopeSuccessful($query)
    {
        return $query->whereBetween('response_code', [200, 299]);
    }

    /**
     * Scope for failed requests.
     */
    public function scopeFailed($query)
    {
        return $query->where('response_code', '>=', 400);
    }

    /**
     * Scope for requests within the last minute (for rate limiting).
     */
    public function scopeLastMinute($query)
    {
        return $query->where('created_at', '>=', now()->subMinute());
    }
}
