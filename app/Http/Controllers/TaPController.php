<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Poll;
use App\Http\Resources\TestResource;
use App\Http\Resources\PollResource;
use App\Http\Resources\QuestionResource;

class TaPController extends Controller
{
    public function createTest(Request $request) {
      $test = new Test();
      $test->name = $request->name;
      $test->email = $request->user()->email;
      $test->status = $request->status;
      $test->save();
      return new TestResource(Test::findOrFail($test->id));
    }

    public function saveTest(Request $request) {
      $test = Test::findOrFail($request->testId);
      $test->name = $request->testName;
      $test->description = $request->testDescription;
      $test->save();
      $questions = $request->questions;

      foreach($questions as $question) {
        $question = json_encode($question);
        $question = json_decode($question, FALSE);

        $questionWhere = Question::findOrFail($question->id);
        $questionWhere->question = $question->name;
        $questionWhere->radio_variants = json_decode(json_encode($question->radioVariants), FALSE);
        $questionWhere->save();
      }
    }

    public function getTest($id) {
      return new TestResource(Test::findOrFail($id));
    }

    public function getQuestions($id) {
      $arr = array();
      $questions = Test::findOrFail($id)->questions;
      foreach ($questions as $question) {
        $arr[] = new QuestionResource($question);
      }
      return $arr;
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
