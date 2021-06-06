<?php

namespace App\Http\Resources;

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
      return [
        'id' => $this->id,
        'name' => $this->name,
        'header' => $this->header,
        'description' => $this->description,
        'imageLink' => !count($this->getMedia('scenarioImages')) ? null : $this->getMedia('scenarioImages')
                                                                          ->sortByDesc('created_at')
                                                                          ->first()
                                                                          ->getFullUrl(),
        'conditions' => ScenarioConditionResource::collection($this->conditions),
        'testHash' => $this->test->hash,
      ];
    }
}
