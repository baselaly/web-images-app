<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'user_name' => $this->full_name,
            'user_image' => $this->image,
            'user_email' => $this->email,
            'user_id' => $this->id
        ];
    }
}
