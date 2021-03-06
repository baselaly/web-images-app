<?php

namespace App\Http\Resources\Follower;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowerResource extends JsonResource
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
            'user_name' => $this->follower->fullname,
            'user_image' => $this->follower->image,
            'user_id' => $this->follower->id
        ];
    }
}
