<?php

return [

    //Token effective days
    'passport_token_ttl' => env('PASSPORT_TOKEN_TTL', 1),

    //Refresh token valid days
    'passport_refresh_token_ttl' => env('PASSPORT_REFRESH_TOKEN_TTL', 7),

    'super_admin' => [
        'provider' => env('JAX_SUPER_ADMIN_PROVIDER', 'admin'),

        'auth' => env('JAX_SUPER_ADMIN_AUTH', 'auth:admin'),

        'guard' => env('JAX_SUPER_ADMIN_GRARD', 'admin')
    ],

    'multi_auth_guards' => env('JAX_MULTI_AUTH_GUARDS'),

];