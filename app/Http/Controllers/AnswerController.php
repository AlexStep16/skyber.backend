<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Test;
use App\Models\DispatchesTest;

class AnswerController extends Controller
{
  public function store(Request $request) {
    $test = Test::where('hash', $request->hash)->first();

    $email = $request->user() ? $request->user()->email : null;
    DispatchesTest::create([
      'email' => $email,
      'test_id' => $test->id,
      'fingerprint' => $request->fingerprint,
    ]);
    $test->count_sub = $test->count_sub + 1;
    $test->save();

    if(!$request->hasStatistic) return true;
    $questionsArray = json_decode(json_encode($request->questions), FALSE);
    foreach($questionsArray as $question) {
      $answers = new Answer();
      $answers->question = $question->name;
      $answers->question_id = $question->id;
      if(gettype($question->checked) == "array") {
        $question->checked = json_encode($question->checked);
      }
      $answers->checked = (string) $question->checked;
      $answers->test_id = $test->id;
      $answers->save();
    }
  }

  public function getAnswers(Request $request, $id) {
    $answers = Answer::where('test_id', $id)->get();
    return AnswerResource::collection($answers);
  }
}
