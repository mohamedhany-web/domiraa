<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'ip_address',
        'user_agent',
        // Legacy columns (for backward compatibility)
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'properties' => 'array',
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the causer (user who performed the action)
     */
    public function causer()
    {
        return $this->morphTo();
    }

    /**
     * Get the subject (entity that was affected)
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Legacy relationship - user who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /**
     * Log an activity with the new format
     */
    public static function logActivity(
        string $description,
        ?string $logName = null,
        ?Model $subject = null,
        ?array $properties = null
    ) {
        return static::create([
            'log_name' => $logName,
            'action' => $logName, // For backwards compatibility
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'causer_type' => auth()->check() ? User::class : null,
            'causer_id' => auth()->id(),
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Legacy log method for backward compatibility
     */
    public static function log(
        string $action,
        ?string $description = null,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ) {
        return static::create([
            'log_name' => $action,
            'action' => $action, // For backwards compatibility
            'description' => $description,
            'subject_type' => $model ? get_class($model) : null,
            'subject_id' => $model?->id,
            'causer_type' => auth()->check() ? User::class : null,
            'causer_id' => auth()->id(),
            'properties' => [
                'old' => $oldValues,
                'new' => $newValues,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logCreate(Model $model, ?string $description = null)
    {
        return static::logActivity(
            $description ?? 'تم إنشاء ' . class_basename($model),
            'create',
            $model,
            ['attributes' => $model->toArray()]
        );
    }

    public static function logUpdate(Model $model, array $oldValues, ?string $description = null)
    {
        return static::logActivity(
            $description ?? 'تم تحديث ' . class_basename($model),
            'update',
            $model,
            ['old' => $oldValues, 'new' => $model->toArray()]
        );
    }

    public static function logDelete(Model $model, ?string $description = null)
    {
        return static::logActivity(
            $description ?? 'تم حذف ' . class_basename($model),
            'delete',
            $model,
            ['attributes' => $model->toArray()]
        );
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('causer_id', $userId)
                     ->where('causer_type', User::class);
    }

    public function scopeForSubject($query, $subjectType, $subjectId = null)
    {
        $query->where('subject_type', $subjectType);
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query;
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByLogName($query, $logName)
    {
        return $query->where('log_name', $logName);
    }
}

