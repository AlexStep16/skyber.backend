<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
      return [
        'id' => $this->id,
        'testName' => $this->name,
        'description' => $this->description,
        'email' => $this->email,
        'status' => $this->status,
        'hash' => $this->hash,
        'countSub' => $this->count_sub,
        'imageLink' => !count($this->getMedia('testImage')) ? null : $this->getMedia('testImage')
                                                                          ->sortByDesc('created_at')
                                                                          ->first()
                                                                          ->getFullUrl(),
        'videoLink' => $this->video_link,
      ];
    }
}
