<?php

namespace App\Http\Requests\Questions;

use Illuminate\Foundation\Http\FormRequest;

class QuestionCreateRequest extends FormRequest
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
        'testId' => 'required|integer',
        'index' => 'required|nullable',
        'variants' => 'array',
        'name' => 'string|nullable',
        'typeAnswer' => 'string',
        'isRequire' => 'required|boolean',
        'right_variants' => 'array',
        'videoLink' => 'string|nullable',
        'hideVideoBox' => 'required|boolean',
      ];
    }
}
