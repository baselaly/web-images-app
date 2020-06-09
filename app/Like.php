<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'likeable_type', 'likeable_id', 'user_id',
    ];

    /**
     * Get the owning commentable model.
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}
