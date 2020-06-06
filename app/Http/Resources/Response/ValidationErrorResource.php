<?php

namespace App\Http\Resources\Response;

use Illuminate\Http\Resources\Json\JsonResource;

class ValidationErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $transformed = [];
        foreach ($this->errors->all() as $message) {
            $transformed[] = $message;
        }

        return [
            'errors' => $transformed,
        ];
    }
}
