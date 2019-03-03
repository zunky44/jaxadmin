<?php

namespace Jagat\Jax\Models;


use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $guarded = ['id'];

    public function permission()
    {
        return $this->hasMany('Jagat\Jax\Models\Permission', 'pg_id');
    }
}