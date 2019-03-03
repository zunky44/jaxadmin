<?php

namespace Jagat\Jax;


use Illuminate\Database\Eloquent\Model;

class AdminUserFactory
{
    /**
     * @return Model
     */
    public static function adminUser()
    {
        $key = 'auth.providers.' . config('jax.super_admin.provider') . '.model';

        return app(config($key));
    }
}