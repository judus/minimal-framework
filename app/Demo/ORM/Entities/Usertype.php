<?php

namespace App\Demo\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

/**
 * Class Usertype
 *
 * @package App\Demo\ORM\Entities
 */
class Usertype extends ORM
{
    /**
     * @var string
     */
    protected $table = 'usertypes';

    /**
     * @return \Maduser\Minimal\Database\ORM\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'usertype_id', 'id');
    }
}