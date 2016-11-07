<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Article extends Model
{
    /**
     * Disable default Article timestamps.
     *
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Fillable fields for an Article.
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
     * Scope a query to only include not ignored Articles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotIgnored($query)
    {
        $articles_ignored = Auth::user()->articles_ignored()->select('article_id')->get();

        return $query->whereNotIn('id', $articles_ignored);
    }

    /**
     * Scope a query to order Articles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sort
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortedBy($query, $sort)
    {
        $attribute = 'created_at';
        $direction = 'desc';

        $params = explode('|', $sort);

        if (!empty($params[0])) {
            switch ($params[0]) {
                case 'created': $attribute = 'created_at'; break;
                case 'likes': $attribute = 'counter_likes'; break;
                default: $attribute = 'created_at'; break;
            }
        }

        if (!empty($params[1])) {
            switch ($params[1]) {
                case 'desc': $direction = 'desc'; break;
                case 'asc': $direction = 'asc'; break;
                default: $direction = 'desc'; break;
            }
        }

        return $query->orderBy($attribute, $direction);
    }

    /**
     * An Article is owned by a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * An Article can have many ArticlesIgnore.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ignores()
    {
        return $this->hasMany('App\ArticlesIgnore');
    }

    /**
     * An Article can have many ArticlesLike.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany('App\ArticlesLike');
    }

    /**
     * An Article can have many Comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * An Article can have many ArticlesPhoto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany('App\ArticlesPhoto');
    }
}
