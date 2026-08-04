<?php

namespace App\Modules\Core\Activity\Traits;

use App\Modules\Core\Activity\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->recordActivity('created');
        });

        static::updated(function ($model) {
            $model->recordActivity('updated');
        });

        static::deleted(function ($model) {
            $model->recordActivity('deleted');
        });
    }

    public function recordActivity(string $action): void
    {
        // Don't log if there is no logged in user (e.g. CLI seeders)
        if (!auth()->check()) {
            return;
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'loggable_type' => static::class,
            'loggable_id' => $this->id,
            'description' => $this->getActivityDescription($action),
            'meta' => [
                'ip' => request()->ip(),
                'agent' => request()->userAgent()
            ]
        ]);
    }

    /**
     * Fallback description. Customize this in individual models.
     */
    protected function getActivityDescription(string $action): string
    {
        $modelName = class_basename(static::class);
        $userName = auth()->user()->name ?? 'System';
        return "{$userName} {$action} {$modelName} (ID: {$this->id})";
    }
}
