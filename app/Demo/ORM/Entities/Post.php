<?php

namespace App\Demo\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

/**
 * Class Post
 *
 * @package App\Demo\ORM\Entities
 */
class Post extends ORM
{
    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @return \Maduser\Minimal\Database\ORM\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return \Maduser\Minimal\Database\ORM\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}