<?php

namespace DigitalIndoorsmen\LaravelActorTrails\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DigitalIndoorsmen\LaravelActorTrails\LaravelActorTrails
 */
class LaravelActorTrails extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DigitalIndoorsmen\LaravelActorTrails\LaravelActorTrails::class;
    }
}
