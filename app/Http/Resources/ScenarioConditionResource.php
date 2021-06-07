<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScenarioConditionResource extends JsonResource
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
        'condition' => $this->condition,
        'scenario_id' => $this->scenario_id,
        'scores' => $this->scores,
        'question' => $this->question ? $this->question : ''
      ];
    }
}
