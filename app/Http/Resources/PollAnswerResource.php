<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Poll;

class PollAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      $type = Poll::findOrFail($this->poll_id);
      $type = $type->type_variants;
      return [
        'id' => $this->id,
        'pollId' => $this->poll_id,
        'answers' => $this->answers,
        'type' => $type
      ];
    }
}
