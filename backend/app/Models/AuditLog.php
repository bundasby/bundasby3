<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'description'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime'
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model
     */
    public function auditable()
    {
        return $this->morphTo('model');
    }

    /**
     * Create audit log entry
     */
    public static function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): self {
        $user = auth()->user();
        $request = request();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'user_email' => $user?->email ?? 'system@bundapaud.surabaya.go.id',
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'description' => $description
        ]);
    }

    /**
     * Log create action
     */
    public static function logCreate(Model $model, ?string $description = null): self
    {
        return self::log(
            'create',
            get_class($model),
            $model->id,
            null,
            $model->toArray(),
            $description ?? "Created " . class_basename($model) . " #{$model->id}"
        );
    }

    /**
     * Log update action
     */
    public static function logUpdate(Model $model, array $oldValues, ?string $description = null): self
    {
        return self::log(
            'update',
            get_class($model),
            $model->id,
            $oldValues,
            $model->toArray(),
            $description ?? "Updated " . class_basename($model) . " #{$model->id}"
        );
    }

    /**
     * Log delete action
     */
    public static function logDelete(Model $model, ?string $description = null): self
    {
        return self::log(
            'delete',
            get_class($model),
            $model->id,
            $model->toArray(),
            null,
            $description ?? "Deleted " . class_basename($model) . " #{$model->id}"
        );
    }

    /**
     * Log login
     */
    public static function logLogin(?string $description = null): self
    {
        return self::log('login', 'User', auth()->id(), null, null, $description ?? 'User logged in');
    }

    /**
     * Log logout
     */
    public static function logLogout(?string $description = null): self
    {
        return self::log('logout', 'User', auth()->id(), null, null, $description ?? 'User logged out');
    }

    /**
     * Log export
     */
    public static function logExport(string $type, ?string $description = null): self
    {
        return self::log('export', $type, null, null, null, $description ?? "Exported {$type} data");
    }

    /**
     * Log failed login attempt
     */
    public static function logFailedLogin(string $email): self
    {
        return self::log(
            'failed_login',
            'User',
            null,
            null,
            ['email' => $email],
            "Failed login attempt for {$email}"
        );
    }

    /**
     * Get action badge color
     */
    public function getActionColor(): string
    {
        return match ($this->action) {
            'create' => 'green',
            'update' => 'blue',
            'delete' => 'red',
            'login' => 'purple',
            'logout' => 'gray',
            'export' => 'yellow',
            'failed_login' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get action icon
     */
    public function getActionIcon(): string
    {
        return match ($this->action) {
            'create' => '➕',
            'update' => '✏️',
            'delete' => '🗑️',
            'login' => '🔐',
            'logout' => '🚪',
            'export' => '📤',
            'failed_login' => '⚠️',
            default => '📌'
        };
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope for filtering by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
