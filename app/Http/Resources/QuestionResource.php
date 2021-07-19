<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ImageOption;

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
      $imageArr = [];
      foreach($this->getMedia('questionImage') as $image) {
        $object = new \stdClass();
        $imageOption = ImageOption::where('media_id', $image->id)->first();

        $object->original_url = $image->getFullUrl();
        $object->id = $image->id;
        $object->align = 'left';
        if(!is_null($imageOption)) {
          $object->width = $imageOption->width;
          $object->height = $imageOption->height;
          $object->align = $imageOption->alignment;
        }
        $imageArr[] = $object;
      }

      return [
        'id' => $this->id,
        'testId' => $this->test_id,
        'question' => $this->question,
        'variants' => $this->variants,
        'index' => $this->index,
        'typeAnswer' => $this->type_answer,
        'images' => $imageArr,
        'answers' => '',
        'isRequire' => $this->is_require,
        'right_variants' => json_decode($this->right_variants, false),
        'videoLink' => $this->video_link,
      ];
    }
}
