<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Http\Resources\TestResource;
use App\Http\Resources\QuestionResource;

class TestController extends Controller
{
    public function createTest(Request $request) {
      $test = new Test();
      $test->name = $request->name;
      $test->email = $request->user()->email;
      $test->status = $request->status;
      $test->hash = sha1(uniqid($test->id, true));
      $test->save();
      return new TestResource(Test::findOrFail($test->id));
    }

    public function deleteTest(Request $request, $id) {
      $test = Test::findOrFail($id);
      if(count($test->getMedia('testImage')) != 0) {
        $mediaItems = $test->getMedia('testImage');
        $mediaItems[0]->delete();
      }

      $questions = Test::findOrFail($id)->questions;
      foreach ($questions as $question) {
        $mediaItems = $question->getMedia('questionImage');
        if(count($mediaItems) > 0) {
          $mediaItems[0]->delete();
        }
      }

      Test::where('id', $id)->where('email', $request->user()->email)->delete();
    }

    public function saveTest(Request $request) {
      $test = Test::findOrFail($request->testId);
      $test->name = $request->testName;
      $test->description = $request->testDescription;
      $test->status = 'done';
      $test->save();
      $questions = $request->questions;

      foreach($questions as $question) {
        $question = json_encode($question);
        $question = json_decode($question, FALSE);
        $questionWhere = Question::findOrFail($question->id);
        $questionWhere->question = $question->name;
        $questionWhere->type_answer = $question->typeAnswer;
        $questionWhere->variants = json_encode($question->selectedVariants);
        $questionWhere->save();
      }
    }

    public function getTest($id) {
      return new TestResource(Test::findOrFail($id));
    }

    public function generate_code_rand() {
      $len = 5;
      $short = "";
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $charslen = strlen($chars);
      for ($i=0; $i<$len; $i++)
      {
              $rnd = rand(0, $charslen);
              $short .= substr($chars, $rnd, 1);
      }
      return $short;
    }

    public function getTestByHash($hash) {
      return new TestResource(Test::where('hash', $hash)->first());
    }

    public function getTestAll(Request $request) {
      $tests = Test::where('email', $request->user()->email)->get();
      $testsArray = [];
      foreach($tests as $test) {
        $testsArray[] = new TestResource($test);
      }

      return $testsArray;
    }

    public function getQuestions($id) {
      $arr = array();
      $questions = Test::findOrFail($id)->questions;
      foreach ($questions as $question) {
        $arr[] = new QuestionResource($question);
      }
      return $arr;
    }

    public function getQuestionsByHash($hash) {
      $arr = array();
      $questions = Test::where('hash', $hash)->first()->questions;
      foreach ($questions as $question) {
        $arr[] = new QuestionResource($question);
      }
      return $arr;
    }

    public function uploadImage(Request $request) {
      $test = Test::findOrFail($request->id);

      if ($test->addMediaFromRequest('testImage')->toMediaCollection('testImage')) {
        $image = $test->getMedia('testImage')->first()->getFullUrl();
      } else {
        $image = null;
      }
      return response()->json(compact('image'));
    }

    public function deleteImage(Request $request) {
      $test = Test::find($request->id);

      $mediaItems = $test->getMedia('testImage');
      $mediaItems[0]->delete();
    }
}
