<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Poll;
use App\Http\Resources\TestResource;
use App\Http\Resources\PollResource;

class StatsController extends Controller
{
  public function getStatsByHash(Request $request, $hash) {
    $test = Test::where('hash', $hash)->first();
    if($test != null) {
      return new TestResource($test);
    }

    $poll = Poll::where('hash', $hash)->first();
    return new PollResource($poll);
  }
}
