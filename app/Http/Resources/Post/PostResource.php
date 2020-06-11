<?php

namespace App\Http\Resources\Post;

use App\Services\LikeService;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isOwner = false;
        $isLiked = false;

        if (auth('api')->check()) {
            $authId = auth('api')->user()->id;
            if ($authId == $this->user_id) {
                $isOwner = true;
            }

            if ($this->likes()->where(['user_id' => $authId, 'likeable_id' => $this->id, 'likeable_type' => 'App\Post'])->first()) {
                $isLiked = true;
            }
        }

        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at->format('j F Y'),
            'images' => $this->images->pluck('image'),
            'user_name' => $this->user->fullname,
            'user_image' => $this->user->image,
            'user_id' => $this->user_id,
            'is_owner' => $isOwner,
            'likes_count' => $this->likes->count(),
            'is_liked' => $isLiked
        ];
    }
}
