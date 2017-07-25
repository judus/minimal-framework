<?php

namespace Acme\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

class Role extends ORM
{
    protected $table = 'roles';

    public function users()
    {
        return $this->belongsToMany(USer::class, 'role_user', 'user_id', 'role_id', 'id');
    }

}