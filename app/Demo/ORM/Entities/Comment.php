<?php

namespace App\Demo\ORM\Entities;

use Maduser\Minimal\Database\ORM\ORM;

/**
 * Class Comment
 *
 * @package App\Demo\ORM\Entities
 */
class Comment extends ORM
{
    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @return \Maduser\Minimal\Database\ORM\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
}