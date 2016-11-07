<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentsLike extends Model
{
    /**
     * Disable default CommentsLike timestamps.
     *
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable fields for an CommentsLike.
     *
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'comment_id'
    ];

    /**
     * An CommentsLike is owned by a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {

        return $this->belongsTo('App\User');
    }

    /**
     * An CommentsLike is owned by an Comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment()
    {

        return $this->belongsTo('App\Comment');
    }
}
