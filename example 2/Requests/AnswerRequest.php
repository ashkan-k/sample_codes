<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
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
            "text" => 'required|max:800',
            "ticket_id" => 'required|numeric',
            "question_id" => 'required|numeric|exists:questions,id',
        ];
    }
}
