<?php

namespace App\Http\Resources\Like;

use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    private $likesCount;

    public function __construct($likesCount)
    {
        $this->likesCount = $likesCount;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['likes_count' => $this->likesCount];
    }
}
