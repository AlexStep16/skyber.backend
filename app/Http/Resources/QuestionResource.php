<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
        'testId' => $this->test_id,
        'question' => $this->question,
        'variants' => $this->variants,
        'index' => $this->index,
        'typeAnswer' => $this->type_answer,
        'imageLink' => !count($this->getMedia('questionImage')) ? null : $this->getMedia('questionImage')
                                                                          ->sortByDesc('created_at')
                                                                          ->first()
                                                                          ->getFullUrl(),
        'answers' => '',
        'isRequire' => $this->is_require,
        'right_variants' => json_decode($this->right_variants, false),
        'videoLink' => $this->video_link,
      ];
    }
}
