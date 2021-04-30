<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Poll;
use App\Http\Resources\TestResource;
use App\Http\Resources\PollResource;

class StatsController extends Controller
{
  public function getStatsByHash(Request $request) {
    $email = $request->user() ? $request->user()->email : '';
    $test = Test::where('hash', $request->hash)->first();
    if($test != null) {
      if($test->email != $email && $test->ip != $request->fingerprint) {
        return response("It's Not Your", 401);
      }
      return new TestResource($test);
    }

    $poll = Poll::where('hash', $request->hash)->first();
    if($poll->email != $email && $poll->ip != $request->fingerprint) {
      return response("It's Not Your", 401);
    }
    return new PollResource($poll);
  }
}
