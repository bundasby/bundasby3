<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ExternalApiConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'base_url',
        'headers',
        'auth_type',
        'api_key',
        'api_secret',
        'timeout',
        'description',
        'is_active',
        'last_sync_at',
    ];

    protected $casts = [
        'headers' => 'array',
        'is_active' => 'boolean',
        'timeout' => 'integer',
        'last_sync_at' => 'datetime',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
    ];

    /**
     * Get the decrypted API key.
     */
    public function getDecryptedApiKey(): ?string
    {
        if ($this->api_key) {
            try {
                return Crypt::decryptString($this->api_key);
            } catch (\Exception $e) {
                return $this->api_key;
            }
        }
        return null;
    }

    /**
     * Get the decrypted API secret.
     */
    public function getDecryptedApiSecret(): ?string
    {
        if ($this->api_secret) {
            try {
                return Crypt::decryptString($this->api_secret);
            } catch (\Exception $e) {
                return $this->api_secret;
            }
        }
        return null;
    }

    /**
     * Set the API key (encrypted).
     */
    public function setApiKeyAttribute($value): void
    {
        if ($value) {
            $this->attributes['api_key'] = Crypt::encryptString($value);
        } else {
            $this->attributes['api_key'] = null;
        }
    }

    /**
     * Set the API secret (encrypted).
     */
    public function setApiSecretAttribute($value): void
    {
        if ($value) {
            $this->attributes['api_secret'] = Crypt::encryptString($value);
        } else {
            $this->attributes['api_secret'] = null;
        }
    }

    /**
     * Scope for active configs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get headers with authentication.
     */
    public function getAuthHeaders(): array
    {
        $headers = $this->headers ?? [];

        switch ($this->auth_type) {
            case 'api_key':
                $headers['X-API-Key'] = $this->getDecryptedApiKey();
                if ($this->api_secret) {
                    $headers['X-API-Secret'] = $this->getDecryptedApiSecret();
                }
                break;
            case 'bearer':
                $headers['Authorization'] = 'Bearer ' . $this->getDecryptedApiKey();
                break;
            case 'basic':
                $credentials = base64_encode($this->getDecryptedApiKey() . ':' . $this->getDecryptedApiSecret());
                $headers['Authorization'] = 'Basic ' . $credentials;
                break;
        }

        return $headers;
    }
}
