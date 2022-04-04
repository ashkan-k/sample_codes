<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if ($this->method() == "post")
            return [
                'name' => 'required|max:250',
                'email' => 'required|string|email|max:255|unique:users',
                'level' => 'required',
                'profile_photo_path' => 'mimes:jpg,jpeg,png,bmp',
                'password' => 'required',
                'status' => 'required',

            ];
        return [
            'name' => 'required|max:250',
            'email' => 'required|string|email|max:255|unique:users',
            'level' => 'required',
            'profile_photo_path' => 'mimes:jpg,jpeg,png,bmp',
            'status' => 'required',
        ];
    }
}
