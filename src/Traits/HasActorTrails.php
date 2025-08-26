<?php

namespace DigitalIndoorsmen\LaravelActorTrails\Traits;

use DigitalIndoorsmen\LaravelActorTrails\Helpers\ActorResolver;

trait HasActorTrails
{
    public static function bootHasActorTrails()
    {
        static::creating(function ($model) {
            $model->created_by = ActorResolver::resolve();
        });

        static::updating(function ($model) {
            $model->modified_by = ActorResolver::resolve();
        });

        static::deleting(function ($model) {
            // Always set deleted_by, regardless of SoftDeletes
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                // Soft delete case
                $model->deleted_by = ActorResolver::resolve();
                $model->saveQuietly();
            } else {
                // Hard delete case (record will be gone, but we still set it before delete)
                $model->deleted_by = ActorResolver::resolve();
            }
        });
    }

    protected $casts = [
        'created_by' => 'array',
        'modified_by' => 'array',
        'deleted_by' => 'array',
    ];
}
