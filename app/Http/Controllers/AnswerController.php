<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Models\Test;
use App\Services\Tests\TestModel;
use App\Models\DispatchesTest;
use App\Services\Answers\AnswerModel;

class AnswerController extends Controller
{
  public function __construct(TestModel $testModel,AnswerModel $answerModel)
  {
    $this->testModel = $testModel;
    $this->answerModel = $answerModel;
  }

  public function store(Request $request)
  {
    if (!$this->testModel->isTestExist($request->hash)) return response('Not Found', 400);
    else $test = Test::where('hash', $request->hash)->first();

    if (!$this->testModel->isMyTest($request, $test)) {
      return response('Not identified', 401);
    }

    $this->answerModel->storeDispatch($request, $test);
    $this->answerModel->store($request, $test);
  }

  public function getAnswers(Request $request, $id)
  {
    $answers = Answer::where('test_id', $id)->get();

    return AnswerResource::collection($answers);
  }
}
