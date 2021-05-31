<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestSettingResource extends JsonResource
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
        'test_id' => $this->test_id,
        'access_for_all' => $this->access_for_all,
        'password_access' => $this->password_access,
        'is_list' => $this->is_list,
        'is_right_questions' => $this->is_right_questions,
        'is_resend' => $this->is_resend,
        'password' => $this->password,
      ];
    }
}
