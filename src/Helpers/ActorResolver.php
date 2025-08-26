<?php

namespace DigitalIndoorsmen\LaravelActorTrails\Helpers;

use Illuminate\Support\Facades\Auth;

class ActorResolver
{
    /**
     * Resolve the current authenticated actor into an array
     * based on the configured attributes.
     *
     * @return array|null
     */
    public static function resolve(): ?array
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $attributes = config('actor-trails.attributes');

        $data = [];
        foreach ($attributes as $key => $resolver) {
            $data[$key] = $resolver($user);
        }

        return $data;
    }
}
