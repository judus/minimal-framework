<?php

namespace Acme\Demo\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

class Comment extends ORM
{
    protected $table = 'comments';

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}