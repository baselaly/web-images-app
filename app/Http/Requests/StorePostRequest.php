<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|image|mimes:jpg,jpeg,png|max:5000',
        ];

        $rules['body'] = request('body') ? 'required|max:5000' : '';

        return $rules;
    }
}
