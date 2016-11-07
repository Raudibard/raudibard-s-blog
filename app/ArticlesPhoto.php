<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class ArticlesPhoto extends Model
{
    /**
     * Disable default ArticlesPhoto timestamps.
     *
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable fields for an ArticlesPhoto.
     *
     * 
     * @var array
     */
    protected $fillable = [
        'filepath'
    ];

    /**
     * An ArticlesPhoto is owned by a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * An ArticlesPhoto is owned by an Article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo('App\Article');
    }
}
