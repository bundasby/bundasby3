<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WebhookEndpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_client_id',
        'name',
        'url',
        'events',
        'secret',
        'is_active',
        'retry_count',
        'timeout',
        'description',
    ];

    protected $casts = [
        'events' => 'array',
        'is_active' => 'boolean',
        'retry_count' => 'integer',
        'timeout' => 'integer',
    ];

    protected $hidden = [
        'secret',
    ];

    /**
     * Available webhook events.
     */
    public const EVENTS = [
        'article.created' => 'Artikel Baru Dibuat',
        'article.updated' => 'Artikel Diperbarui',
        'article.deleted' => 'Artikel Dihapus',
        'data_paud.created' => 'Data PAUD Baru',
        'data_paud.updated' => 'Data PAUD Diperbarui',
        'bunda_paud.updated' => 'Data Bunda PAUD Diperbarui',
        'mitra_paud.registered' => 'Mitra PAUD Baru Terdaftar',
        'mitra_paud.approved' => 'Mitra PAUD Disetujui',
        'complaint.new' => 'Pengaduan Baru Masuk',
        'complaint.responded' => 'Pengaduan Direspon',
        'statistics.updated' => 'Statistik Diperbarui',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($webhook) {
            if (empty($webhook->secret)) {
                $webhook->secret = Str::random(64);
            }
        });
    }

    /**
     * Get the API client that owns this webhook.
     */
    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }

    /**
     * Get the logs for this webhook.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    /**
     * Check if this webhook subscribes to a specific event.
     */
    public function subscribesTo(string $event): bool
    {
        $events = $this->events ?? [];

        // Check for wildcard
        if (in_array('*', $events)) {
            return true;
        }

        return in_array($event, $events);
    }

    /**
     * Scope for active webhooks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for webhooks subscribed to a specific event.
     */
    public function scopeForEvent($query, string $event)
    {
        return $query->where(function ($q) use ($event) {
            $q->whereJsonContains('events', $event)
                ->orWhereJsonContains('events', '*');
        });
    }

    /**
     * Generate signature for payload.
     */
    public function signPayload(array $payload): string
    {
        $payloadJson = json_encode($payload);
        return hash_hmac('sha256', $payloadJson, $this->secret);
    }
}
