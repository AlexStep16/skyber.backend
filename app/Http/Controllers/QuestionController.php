<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Resources\QuestionResource;
use App\Models\ImageOption;

class QuestionController extends Controller
{
  public function create(Request $request) {
    $question = new Question();
    $question->test_id = $request->testId;
    $question->variants = json_encode($request->standartVariants);
    $question->question = $request->name;
    $question->is_require = $request->isRequire;
    $question->video_link = $request->videoLink;
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

    if($question == null) return response('Not Found', 400);

    for($i = 0; $i < $request->countImages; $i++) {
      if (
        $media = $question->addMediaFromRequest("questionImage{$i}")
              ->usingFileName(rand() . $i . '.' . $request["imageType{$i}"])
              ->addCustomHeaders([
                 'ACL' => 'public-read'
              ])
              ->toMediaCollection('questionImage', 's3')
      ) {
        $id = $media->id;
        $mediaOption = new ImageOption();
        $mediaOption->alignment = 'left';
        $mediaOption->media_id = $id;
        $mediaOption->save();
      }
    }

    return new QuestionResource($question);
  }

  public function deleteImage(Request $request) {
    $question = Question::find($request->id);

    $mediaItems = $question->getMedia('questionImage');
    $mediaItems[0]->delete();
  }
}
