<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'email' => 'required|email|max:190|unique:users,email',
            'password' => 'required|min:8|max:100|confirmed',
        ];

        $rules['image'] = request('image') ? 'required|image|mimes:jpg,jpeg,png|max:5000' : '';

        return $rules;
    }
}
