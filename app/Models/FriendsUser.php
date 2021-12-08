<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendsUser extends Model
{
    use HasFactory;

    public function timings() {
      return $this->belongsTo(FriendsTiming::class, 'friends_timing_id');
    }
}
