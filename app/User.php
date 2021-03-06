<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'bio', 'active', 'image', 'activation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'slug', 'followers_count', 'followings_count', 'fullname',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value;
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = $value;
    }

    public function setBioAttribute($value)
    {
        $this->attributes['bio'] = $value;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = $value;
    }

    public function setImageAttribute($value)
    {
        if (!$value) {
            return;
        }

        $imageName = time() . '_' . uniqid() . '.' . $value->getClientOriginalExtension();

        if (!Storage::disk('users')->put($imageName, File::get($value))) {
            throw new \Exception('error in uploading image');
        }

        $this->attributes['image'] = $imageName;
    }

    public function getSlugAttribute()
    {
        return str_slug($this->first_name . ' ' . $this->last_name, '-');
    }

    public function getFollowersCountAttribute()
    {
        return $this->activeFollowings()->count();
    }

    public function getFollowingsCountAttribute()
    {
        return $this->activeFollowers()->count();
    }

    public function getFirstNameAttribute($value)
    {
        return $value;
    }

    public function getLastNameAttribute($value)
    {
        return $value;
    }

    public function getBioAttribute($value)
    {
        return $value;
    }

    public function getEmailAttribute($value)
    {
        return $value;
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            return asset('storage/users/user.png');
        }

        return asset('storage/users/' . $value);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getActiveAttribute($value)
    {
        return $value;
    }

    public function followers()
    {
        return $this->hasMany('App\UserFollower', 'user_id');
    }

    public function followings()
    {
        return $this->hasMany('App\UserFollower', 'follower_id');
    }

    public function activeFollowers()
    {
        return $this->hasMany('App\UserFollower', 'user_id')->whereHas('follower', function ($query) {
            $query->active();
        });
    }

    public function activeFollowings()
    {
        return $this->hasMany('App\UserFollower', 'follower_id')->whereHas('user', function ($query) {
            $query->active();
        });
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
