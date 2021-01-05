<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Poll;
use App\Http\Resources\TestResource;
use App\Http\Resources\PollResource;

class TaPController extends Controller
{
    public function createTest(Request $request) {
      $test = new Test();
      $test->name = $request->name;
      $test->email = $request->email;
      $test->status = $request->status;
      $test->save();
      return new TestResource(Test::findOrFail($test->id));
    }

    public function createPoll(Request $request) {
      $poll = new Poll;
      $poll->name = $request->name;
      $test->email = $request->email;
      $test->status = $request->status;
      $poll->save();
      return new PollResource(Poll::findOrFail($poll->id));
    }
}
