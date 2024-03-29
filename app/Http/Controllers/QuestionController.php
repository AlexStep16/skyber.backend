<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\QuestionResource;

use App\Models\Question;
use App\Models\ImageOption;

use App\Services\Questions\QuestionModel;

use App\Http\Requests\Questions\{QuestionCreateRequest, QuestionDeleteImageRequest};

class QuestionController extends Controller
{
  /**
   * Undocumented function
   *
   * @param QuestionModel $questionModel
   */
  public function __construct(QuestionModel $questionModel)
  {
    $this->questionModel = $questionModel;
  }

  /**
   * Undocumented function
   *
   * @param QuestionCreateRequest $request
   * @return Int
   */
  public function create(QuestionCreateRequest $request): Int {
    $validatedRequest = $request->validated();

    return $this->questionModel->create($validatedRequest);
  }

  /**
   * Undocumented function
   *
   * @param Int $id
   * @return void
   */
  public function delete(Int $id)
  {
    $question = Question::findOrFail($id);

    if (count($question->getMedia('questionImage')) !== 0) {
      $mediaItems = $question->getMedia('questionImage');
      $mediaItems[0]->delete();
    }

    $question->delete();
  }

  /**
   * Undocumented function
   *
   * @param Request $request
   * @return QuestionResource
   */
  public function uploadImage(Request $request)
  {
    $question = Question::findOrFail($request['id']);

    if(is_null($question)) return response('Not Found', 400);

    $this->questionModel->addMediaToQuestion($request, $question);

    return new QuestionResource($question);
  }

  /**
   * Undocumented function
   *
   * @param QuestionDeleteImageRequest $request
   * @return void
   */
  public function deleteImage(QuestionDeleteImageRequest $request)
  {
    $validatedRequest = $request->validated();

    $question = Question::find($validatedRequest['id']);

    $mediaItems = $question->getMedia('questionImage');
    $mediaItems[0]->delete();
  }
}
