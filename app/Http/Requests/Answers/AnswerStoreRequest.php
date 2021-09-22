<?php

namespace App\Http\Requests\Answers;

use Illuminate\Foundation\Http\FormRequest;

class AnswerStoreRequest extends FormRequest
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
        'hash' => 'required|string',
        'fingerprint' => 'required|string',
        'has_statistic' => 'string|nullable',
        'questions' => 'array',
      ];
    }
}
