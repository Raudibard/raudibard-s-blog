<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * A User can have many Articles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    /**
     * A User can have many ArticlesPhoto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles_photos()
    {
        return $this->hasMany('App\ArticlesPhoto');
    }

    /**
     * A User can have many ArticlesIgnore.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles_ignored()
    {
        return $this->hasMany('App\ArticlesIgnore');
    }

    /**
     * A User can have many ArticlesLike.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles_liked()
    {
        return $this->hasMany('App\ArticlesLike');
    }

    /**
     * A User can have many Comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * A User can have many CommentsLike.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments_liked()
    {
        return $this->hasMany('App\CommentsLike');
    }
}
