<?php

namespace App\Http\Resources;

use App\Models\ImageOption;
use Illuminate\Http\Resources\Json\JsonResource;

class ScenarioResource extends JsonResource
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
      foreach($this->getMedia('scenarioImage') as $image) {
        $object = new \stdClass();
        $imageOption = ImageOption::where('media_id', $image->id)->first();

        $object->original_url = $image->getFullUrl();
        $object->id = $image->id;
        $object->align = 'left';
        if(!is_null($imageOption)) {
          $object->align = $imageOption->alignment;
        }
        $imageArr[] = $object;
      }

      return [
        'id' => $this->id,
        'name' => $this->name,
        'header' => $this->header,
        'description' => $this->description,
        'imageLink' => $imageArr,
        'conditions' => ScenarioConditionResource::collection($this->conditions),
        'testHash' => $this->test->hash,
      ];
    }
}
