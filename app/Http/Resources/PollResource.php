<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PollResource extends JsonResource
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
        'pollName' => $this->name,
        'pollDescription' => $this->description,
        'email' => $this->email,
        'variants' => $this->variants,
        'hash' => $this->hash,
        'countSub' => $this->count_sub,
        'typeVariants' => $this->type_variants,
        'imageLink' => !count($this->getMedia('pollImage')) ? null : $this->getMedia('pollImage')
                                                                          ->sortByDesc('created_at')
                                                                          ->first()
                                                                          ->getFullUrl(),
      ];
    }
}
