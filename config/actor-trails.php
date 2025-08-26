<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Actor Attributes
    |--------------------------------------------------------------------------
    | Define which attributes should be stored in the JSON object.
    | You can override this per model by implementing `getActorAttributes()`.
    */
    'attributes' => [
        'id' => fn($user) => $user->getAuthIdentifier(),
        'user_type' => fn($user) => class_basename($user),
        'auth_table' => fn($user) => $user->getTable(),
        'display_name' => fn($user) => $user->name ?? $user->username ?? 'Unknown',
    ],

];
