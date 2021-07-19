<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TestSettingResource;
use App\Models\ImageOption;

class TestResource extends JsonResource
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
      foreach($this->getMedia('testImage') as $image) {
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
        'testName' => $this->name,
        'description' => $this->description,
        'email' => $this->email,
        'status' => $this->status,
        'hash' => $this->hash,
        'countSub' => $this->count_sub,
        'imageLink' => $imageArr,
        'videoLink' => $this->video_link,
        'fingerprint' => $this->ip,
        'settings' => TestSettingResource::collection($this->settings),
      ];
    }
}
