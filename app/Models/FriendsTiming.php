<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendsTiming extends Model
{
    use HasFactory;

    public function users() {
      return $this->hasMany(FriendsUser::class, 'friends_timing_id');
    }
}
