<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'bio' => $this->bio,
            'image' => $this->image,
            'email' => $this->email,
            'followings' => $this->followings->count(),
            'followers' => $this->followers->count()
        ];
    }
}
