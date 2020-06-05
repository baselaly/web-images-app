<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required|max:250',
            'last_name' => 'required|max:250',
        ];

        $rules['image'] = request('image') ? 'required|image|mimes:jpg,jpeg,png|max:5000' : '';
        $rules['bio'] = request('bio') ? 'required|max:1000' : '';
        $rules['password'] = request('password') ? 'required|min:8|max:100|confirmed' : '';
        return $rules;
    }
}
