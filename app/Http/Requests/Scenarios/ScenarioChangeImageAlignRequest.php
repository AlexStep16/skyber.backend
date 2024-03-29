<?php

namespace App\Http\Requests\Scenarios;

use Illuminate\Foundation\Http\FormRequest;

class ScenarioChangeImageAlignRequest extends FormRequest
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
          'media_id' => 'required|numeric',
          'align' => 'required|string',
        ];
    }
}
