<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body', 'active', 'user_id',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['images', 'user'];

    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = $value;
    }

    public function getBodyAttribute($value)
    {
        return $value;
    }

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = $value;
    }

    public function getActiveAttribute($value)
    {
        return $value;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function images()
    {
        return $this->hasMany('App\PostImage');
    }

    public function likes()
    {
        return $this->morphMany('App\Like', 'likeable');
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }
}
