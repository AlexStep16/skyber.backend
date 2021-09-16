<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Resources\QuestionResource;
use App\Models\ImageOption;
use App\Services\Questions\QuestionModel;

class QuestionController extends Controller
{
  public function __construct(QuestionModel $questionModel)
  {
    $this->questionModel = $questionModel;
  }

  public function create(Request $request) {
    return $this->questionModel->create($request);
  }

  public function delete($id) {
    $question = Question::findOrFail($id);

    if(count($question->getMedia('questionImage')) != 0) {
      $mediaItems = $question->getMedia('questionImage');
      $mediaItems[0]->delete();
    }
    $question->delete();
  }

  public function uploadImage(Request $request) {
    $question = Question::findOrFail($request->id);
    if(is_null($question)) return response('Not Found', 400);

    $this->questionModel->addMediaToQuestion($request);

    return new QuestionResource($question);
  }

  public function deleteImage(Request $request) {
    $question = Question::find($request->id);

    $mediaItems = $question->getMedia('questionImage');
    $mediaItems[0]->delete();
  }
}
