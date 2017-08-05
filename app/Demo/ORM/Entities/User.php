<?php

namespace App\Demo\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

/**
 * Class User
 *
 * @package App\Demo\ORM\Entities
 */
class User extends ORM
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var bool
     */
    protected $timestamps = true;

    /**
     * @return \Maduser\Minimal\Database\ORM\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    /**
     * @return \Maduser\Minimal\Database\ORM\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    /**
     * @return \Maduser\Minimal\Database\ORM\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    /**
     * @return \Maduser\Minimal\Database\ORM\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * @return \Maduser\Minimal\Database\ORM\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Usertype::class, 'usertype_id', 'id');
    }

}