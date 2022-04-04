<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
        return [
            'user_id' => 'required',
            'name' => 'required',
            'lastName' => 'required',
            'address' => 'required',
            'cv' => 'required',
            'nationalCode' => 'required|min:10|max:10|unique:teachers',
            'barthDay' => 'required',
            'status' => 'required',
            'phoneNumber' => 'required|unique:teachers|numeric',
            'description' => 'required|max:250'
        ];
    }
}
