<?php

namespace App\Services\Answers;

use App\Models\Answer;
use App\Models\DispatchesTest;

class AnswerModel
{
  public function store($request, $test)
  {
    if (!$request->hasStatistic) return true;

    $questionsArray = json_decode(json_encode($request->questions), FALSE);
    foreach ($questionsArray as $question) {
      $answers = new Answer();
      $answers->question = $question->name;
      $answers->question_id = $question->id;
      if (gettype($question->checked) == "array") {
        $question->checked = json_encode($question->checked);
      }
      $answers->checked = (string) $question->checked;
      $answers->test_id = $test->id;
      $answers->save();
    }
  }

  public function storeDispatch($request, $test)
  {
    DispatchesTest::create([
      'email' => $request->user() ? $request->user()->email : null,
      'test_id' => $test->id,
      'fingerprint' => $request->fingerprint,
    ]);

    $test->count_sub = $test->count_sub + 1;
    $test->save();
  }
}
