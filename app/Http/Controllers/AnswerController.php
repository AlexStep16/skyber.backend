<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use App\Http\Resources\AnswerResource;

use App\Models\Answer;
use App\Models\Test;
use App\Models\DispatchesTest;

use App\Services\Tests\TestModel;
use App\Services\Answers\AnswerModel;

use App\Http\Requests\Answers\AnswerStoreRequest;

class AnswerController extends Controller
{
  /**
   * Undocumented function
   *
   * @param TestModel $testModel
   * @param AnswerModel $answerModel
   */
  public function __construct(TestModel $testModel,AnswerModel $answerModel)
  {
    $this->testModel = $testModel;
    $this->answerModel = $answerModel;
  }

  /**
   * Undocumented function
   *
   * @param AnswerStoreRequest $request
   * @return void
   */
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

  /**
   * Undocumented function
   *
   * @param Request $request
   * @param Int $id
   * @return AnonymousResourceCollection
   */
  public function getAnswers(Request $request, Int $id): AnonymousResourceCollection
  {
    $answers = Answer::where('test_id', $id)->get();

    return AnswerResource::collection($answers);
  }
}
