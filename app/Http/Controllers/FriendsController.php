<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FriendsUser;
use App\Models\FriendsTiming;

use App\Http\Resources\FriendsTimingResource;


class FriendsController extends Controller
{
    public function index() {
      return FriendsTimingResource::collection(FriendsTiming::all()->orderby('id'));
    }

    public function login(Request $request) {
      if (count(FriendsUser::where('uid', $request['uid'])->get()) === 0) {
        return response('Unauthorized', 401);
      } else {
        return response('Ok', 200);
      }
    }

    public function changeTiming(Request $request) {
      $user = FriendsUser::where('uid', $request['uid'])->first();
      if ($user->friends_timing_id === $request['timing_id']) {
        $user->friends_timing_id = NULL;
      } else {
        $user->friends_timing_id = $request['timing_id'];
      }
      $user->save();
    }
}
