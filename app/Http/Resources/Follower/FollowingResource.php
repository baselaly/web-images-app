<?php

namespace App\Http\Resources\Follower;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowingResource extends JsonResource
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
            'user_name' => $this->user->fullname,
            'user_image' => $this->user->image,
            'user_id' => $this->user->id
        ];
    }
}
