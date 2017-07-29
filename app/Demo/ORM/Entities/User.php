<?php

namespace Acme\Demo\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

class User extends ORM
{
    protected $table = 'users';

    protected $timestamps = true;

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'role_id', 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(Usertype::class, 'usertype_id', 'id');
    }

}