<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PollAnswerResource;
use App\Models\PollAnswer;
use App\Models\Poll;
use App\Models\DispatchesPoll;

class PollAnswerController extends Controller
{
  public function store(Request $request) {
    $answers = new PollAnswer();
    $answers->poll_id = $request->pollId;
    $answers->answers = json_encode($request->selected);
    $answers->save();

    $email = $request->user() ? $request->user()->email  : '';
    DispatchesPoll::create([
      'email' => $email,
      'poll_id' => $request->pollId,
      'fingerprint' => $request->fingerprint,
    ]);

    $poll = Poll::findOrFail($request->pollId);
    $poll->count_sub = $poll->count_sub + 1;
    $poll->save();
  }

  public function getAnswers(Request $request, $id) {
    $answers = PollAnswer::where('poll_id', $id)->get();
    $answersArr = [];
    foreach($answers as $answer) {
      $answersArr[] = new PollAnswerResource($answer);
    }

    return $answersArr;
  }
}
