<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticlesIgnore extends Model
{
    /**
     * Disable default ArticlesIgnore timestamps.
     *
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable fields for an ArticlesIgnore.
     *
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'article_id'
    ];

    /**
     * An ArticlesIgnore is owned by a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {

        return $this->belongsTo('App\User');
    }

    /**
     * An ArticlesIgnore is owned by an Article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {

        return $this->belongsTo('App\Article');
    }
}
