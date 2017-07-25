<?php

namespace Acme\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

class Usertype extends ORM
{
    protected $table = 'usertypes';

    public function users()
    {
        return $this->hasMany(User::class, 'usertype_id', 'id');
    }
}