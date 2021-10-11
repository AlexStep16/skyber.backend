<?php

namespace App\Services\Questions;

use App\Models\Question;
use App\Models\ImageOption;

class QuestionModel
{
  public function create($request)
  {
    $question = new Question();
    $question->test_id = $request['testId'];
    $question->variants = json_encode($request['variants']);
    $question->question = $request['name'];
    $question->is_require = $request['isRequire'];
    $question->video_link = $request['videoLink'];
    $question->index = $request['index'];
    $question->save();

    return $question->id;
  }

  public function addDefaultImageOption($id)
  {
    $mediaOption = new ImageOption();
    $mediaOption->alignment = 'left';
    $mediaOption->media_id = $id;
    $mediaOption->save();
  }

  public function addMediaToQuestion($request, $question)
  {
    for($i = 0; $i < $request['countImages']; $i++) {
      if (
        $media = $question->addMediaFromRequest("questionImage{$i}")
              ->usingFileName(rand() . $i . '.' . $request["imageType{$i}"])
              ->addCustomHeaders([
                 'ACL' => 'public-read'
              ])
              ->toMediaCollection('questionImage', 's3')
      ) {
        $id = $media->id;

        $this->addDefaultImageOption($id);
      }
    }
  }

}
