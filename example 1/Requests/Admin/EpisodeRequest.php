<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EpisodeRequest extends FormRequest
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
            'title' => 'required|max:100',
            'description' => 'required',
            'body' => 'required',
            'video_URL' => 'required',
            'tags' => 'required',
            'keywords' => 'required',
            'number' => 'required',
            'time' => 'required',
            'type' => 'required',
            'course_id' => 'required',
        ];
    }
}
