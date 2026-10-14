<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity($model, 'create');
        });

        static::updated(function ($model) {
            $changes = [
                'old' => array_intersect_key($model->getOriginal(), $model->getChanges()),
                'new' => $model->getChanges()
            ];

            self::logActivity($model, 'update', $changes);
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'delete');
        });
    }

    protected static function logActivity($model, $action, $changes = null)
    {
        if (!Auth::guard('admin')->check()) return;

        $user = Auth::guard('admin')->user();

        // ✅ Only staff
        if ($user->role !== 'staff') return;

        ActivityLog::create([
            'user_id' => $user->id,
            'module' => strtolower(class_basename($model)),
            'action' => $action,
            'record_id' => $model->id,
            'description' => ucfirst($action) . ' ' . class_basename($model) . ' (ID: ' . $model->id . ')',
            'changes' => json_encode($changes),
        ]);
    }
}
