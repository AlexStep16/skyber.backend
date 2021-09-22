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
  public function __construct(QuestionModel $questionModel)
  {
    $this->questionModel = $questionModel;
  }

  public function create(QuestionCreateRequest $request) {
    $validatedRequest = $request->validated();

    return $this->questionModel->create($validatedRequest);
  }

  public function delete($id)
  {
    $question = Question::findOrFail($id);

    if (count($question->getMedia('questionImage')) !== 0) {
      $mediaItems = $question->getMedia('questionImage');
      $mediaItems[0]->delete();
    }

    $question->delete();
  }

  public function uploadImage(Request $request)
  {
    $question = Question::findOrFail($request['id']);

    if(is_null($question)) return response('Not Found', 400);

    $this->questionModel->addMediaToQuestion($request);

    return new QuestionResource($question);
  }

  public function deleteImage(QuestionDeleteImageRequest $request)
  {
    $validatedRequest = $request->validated();

    $question = Question::find($validatedRequest['id']);

    $mediaItems = $question->getMedia('questionImage');
    $mediaItems[0]->delete();
  }
}
