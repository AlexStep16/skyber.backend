<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      return [
        'id' => $this->id,
        'questionId' => $this->question_id,
        'question' => $this->question,
        'checked' => json_decode($this->checked, FALSE),
        'testId' => $this->test_id,
      ];
    }
}
