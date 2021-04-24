<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
  public function create(Request $request) {
    $question = new Question();
    $question->test_id = $request->testId;
    $question->variants = json_encode($request->standartVariants);
    $question->question = $request->name;
    $question->is_require = $request->isRequire;
    $question->index = $request->index;
    $question->save();
    return $question->id;
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

    if ($question->addMediaFromRequest('questionImage')->toMediaCollection('questionImage')) {
      $image = $question->getMedia('questionImage')->first()->getFullUrl();
    } else {
      $image = null;
    }
    return response()->json(compact('image'));
  }

  public function deleteImage(Request $request) {
    $question = Question::find($request->id);

    $mediaItems = $question->getMedia('questionImage');
    $mediaItems[0]->delete();
  }
}
