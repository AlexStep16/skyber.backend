<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\AnswerResource;

use App\Models\Answer;
use App\Models\Test;
use App\Models\DispatchesTest;

use App\Services\Tests\TestModel;
use App\Services\Answers\AnswerModel;

use App\Http\Requests\Answers\AnswerStoreRequest;

class AnswerController extends Controller
{
  public function __construct(TestModel $testModel,AnswerModel $answerModel)
  {
    $this->testModel = $testModel;
    $this->answerModel = $answerModel;
  }

  public function store(AnswerStoreRequest $request)
  {
    $validatedRequest = $request->validated();

    if (!$this->testModel->isTestExist($validatedRequest['hash'])) return response('Not Found', 400);
    else $test = Test::where('hash', $validatedRequest['hash'])->first();

    if (!$this->testModel->isMyTest($validatedRequest, $test)) {
      return response('Not identified', 401);
    }

    $this->answerModel->storeDispatch($validatedRequest, $test);
    $this->answerModel->store($validatedRequest, $test);
  }

  public function getAnswers(Request $request, $id)
  {
    $answers = Answer::where('test_id', $id)->get();

    return AnswerResource::collection($answers);
  }
}
