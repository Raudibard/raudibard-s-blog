<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Comment extends Model
{
    /**
     * Disable default Comment timestamps.
     *
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable fields for a Comment.
     *
     * 
     * @var array
     */
    protected $fillable = [
        'content',
        'edited_at',
        'counter_comments',
        'counter_likes'
    ];

    /**
     * Additional fields to treat as Carbon instances.
     *
     * 
     * @var array
     */
    protected $dates = ['created_at', 'edited_at'];

    /**
     * A Comment is owned by a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * A Comment is owned by an Article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    /**
     * A Comment can have many CommentsLike.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany('App\CommentsLike');
    }

    /**
     * A Comment is owned by a Comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }

    /**
     * A Comment can have many Comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
