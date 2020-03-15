<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFollower extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'follower_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function follower()
    {
        return $this->belongsTo('App\User', 'follower_id');
    }

}
