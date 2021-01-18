<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Test;

class AnswerController extends Controller
{
  public function store(Request $request) {
    $questionsArray = json_decode(json_encode($request->questions), FALSE);

    foreach($questionsArray as $question) {
      $answers = new Answer();
      $answers->question = $question->name;
      $answers->question_id = $question->id;
      $answers->checked = $question->checked;
      $answers->test_id = $request->testId;
      $answers->save();
    }

    $test = Test::findOrFail($request->testId);
    $test->count_sub = $test->count_sub + 1;
    $test->save();
  }

  public function getAnswers(Request $request, $id) {
    $answers = Answer::where('test_id', $id)->get();
    $answersArr = [];
    foreach($answers as $answer) {
      $answersArr[] = new AnswerResource($answer);
    }

    return $answersArr;
  }
}
