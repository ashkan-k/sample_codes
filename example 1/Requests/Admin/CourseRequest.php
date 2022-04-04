<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
                'title' => 'required|max:250',
                'description' => 'required',
                'body' => 'required',
                'images' => 'required|mimes:jpg,jpeg,png,bmp',
                'tags' => 'required',
                'keywords' => 'required',
                'price' => 'required|integer',
                'time' => 'required',
                'status' => 'required',
                'type' => 'required',
                'category_id' => 'required',
                'teacher_id' => 'required',
            ];

        return [
            'title' => 'required|max:250',
            'description' => 'required',
            'body' => 'required',
            'tags' => 'required',
            'keywords' => 'required',
            'price' => 'required|integer',
            'time' => 'required',
            'status' => 'required',
            'type' => 'required',
            'category_id' => 'required',
            'teacher_id' => 'required',

        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'price' => to_english_numbers($this->input('price'))
        ]);
    }
}
