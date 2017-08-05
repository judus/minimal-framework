<?php

namespace App\Demo\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

/**
 * Class Role
 *
 * @package App\Demo\ORM\Entities
 */
class Role extends ORM
{
    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * @return \Maduser\Minimal\Database\ORM\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(USer::class, 'role_user', 'user_id', 'role_id');
    }

}