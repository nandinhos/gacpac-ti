<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAudit('created', $model);
        });

        static::updated(function ($model) {
            self::logAudit('updated', $model);
        });

        static::deleted(function ($model) {
            self::logAudit('deleted', $model);
        });
    }

    protected static function logAudit($event, $model)
    {
        $oldValues = null;
        $newValues = null;

        if ($event === 'updated') {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();
        } elseif ($event === 'created') {
            $newValues = $model->getAttributes();
        } elseif ($event === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
