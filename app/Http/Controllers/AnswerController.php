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
    $questionsArray = json_decode(json_encode($request->questions), FALSE);

    foreach($questionsArray as $question) {
      $answers = new Answer();
      $answers->question = $question->name;
      $answers->question_id = $question->id;
      if(gettype($question->checked) == "array") {
        $question->checked = json_encode($question->checked);
      }
      $answers->checked = (string) $question->checked;
      $answers->test_id = $request->testId;
      $answers->save();
    }
    $email = $request->user() ? $request->user()->email  : '';
    DispatchesTest::create([
      'email' => $email,
      'test_id' => $request->testId,
      'fingerprint' => $request->fingerprint,
    ]);
    $test = Test::findOrFail($request->testId);
    $test->count_sub = $test->count_sub + 1;
    $test->save();
  }

  public function getAnswers(Request $request, $id) {
    $answers = Answer::where('test_id', $id)->get();
    return AnswerResource::collection($answers);
  }
}
