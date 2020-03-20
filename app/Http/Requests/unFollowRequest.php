<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JWTAuth;

class unFollowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = JWTAuth::parseToken()->authenticate()->id;

        return [
            'user_follow_id' => ['required', 'exists:user_followers,id,follower_id,' . $userId],
        ];
    }
}
