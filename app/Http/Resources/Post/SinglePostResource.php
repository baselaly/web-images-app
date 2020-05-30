<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;

class SinglePostResource extends JsonResource
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

        if (auth('api')->check()) {
            if (auth('api')->user()->id == $this->user_id) {
                $isOwner = true;
            }
        }

        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at->format('j F Y'),
            'images' => $this->images->pluck('image'),
            'user_name' => $this->user->fullname,
            'user_image' => $this->user->image,
            'user_id' => $this->user->id,
            'is_owner' => $isOwner
        ];
    }
}
