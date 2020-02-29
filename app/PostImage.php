<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image', 'post_id',
    ];

    public function setImageAttribute($value)
    {
        if (!$value) {
            return;
        }

        $imageName = time() . '_' . uniqid() . '.' . $value->getClientOriginalExtension();

        if (!Storage::disk('images')->put($imageName, File::get($value))) {
            throw new \Exception('error in uploading image');
        }

        $this->attributes['image'] = $imageName;
    }

    public function getImageAttribute($value)
    {
        return asset('storage/images/' . $value);
    }

    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
