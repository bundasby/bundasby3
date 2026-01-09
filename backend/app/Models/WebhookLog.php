<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'webhook_endpoint_id',
        'event',
        'payload',
        'response_code',
        'response_body',
        'status',
        'attempt',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'response_code' => 'integer',
        'attempt' => 'integer',
        'sent_at' => 'datetime',
    ];

    /**
     * Status constants.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    /**
     * Get the webhook endpoint.
     */
    public function webhookEndpoint(): BelongsTo
    {
        return $this->belongsTo(WebhookEndpoint::class);
    }

    /**
     * Scope for pending logs.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for failed logs that can be retried.
     */
    public function scopeRetryable($query)
    {
        return $query->where('status', self::STATUS_FAILED)
            ->whereHas('webhookEndpoint', function ($q) {
                $q->whereRaw('webhook_logs.attempt < webhook_endpoints.retry_count');
            });
    }

    /**
     * Scope for successful logs.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    /**
     * Mark as successful.
     */
    public function markAsSuccess(int $responseCode, ?string $responseBody = null): void
    {
        $this->update([
            'status' => self::STATUS_SUCCESS,
            'response_code' => $responseCode,
            'response_body' => $responseBody,
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark as failed.
     */
    public function markAsFailed(int $responseCode, ?string $errorMessage = null, ?string $responseBody = null): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'response_code' => $responseCode,
            'response_body' => $responseBody,
            'error_message' => $errorMessage,
            'sent_at' => now(),
        ]);
    }

    /**
     * Increment attempt for retry.
     */
    public function incrementAttempt(): void
    {
        $this->increment('attempt');
        $this->update(['status' => self::STATUS_PENDING]);
    }
}
