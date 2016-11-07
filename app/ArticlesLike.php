<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticlesLike extends Model
{
    /**
     * Disable default ArticlesLike timestamps.
     *
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable fields for an ArticlesLike.
     *
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'article_id'
    ];

    /**
     * An ArticlesLike is owned by a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {

        return $this->belongsTo('App\User');
    }

    /**
     * An ArticlesLike is owned by an Article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {

        return $this->belongsTo('App\Article');
    }
}
