<?php
// app/Traits/LogsActivity.php

namespace App\Traits;

use App\Models\LogActivite;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            static::logActivity('create', $model);
        });

        static::updated(function ($model) {
            static::logActivity('update', $model);
        });

        static::deleted(function ($model) {
            static::logActivity('delete', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        if (!auth()->check()) return;

        LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'action' => $action,
            'module' => class_basename($model),
            'id_element' => $model->id,
            'adresse_ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'details' => json_encode($model->getChanges())
        ]);
    }
}
