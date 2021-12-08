<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendsTimingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $users = $this->users;
        return [
          "id" => $this->id,
          "time" => $this->time,
          "direction" => $this->direction,
          "users" => $users,
        ];
    }
}
