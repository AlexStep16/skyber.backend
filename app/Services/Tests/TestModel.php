<?php

namespace App\Services\Tests;

use App\Models\Question;
use App\Models\Test;
use App\Models\TestSetting;
use App\Models\DispatchesTest;
use App\Models\ImageOption;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestModel
{

  public function __construct() {
    $this->email = Auth::user() ? Auth::user()->email : null;
  }

  public function isMyTest($request, $test)
  {
    if ($test->email === null) {
      if ($test->ip !== $request['fingerprint']) {
        return false;
      }
    } else {
      if (
        $test->email !== $this->email
        && $test->ip !== $request['fingerprint']
      ) {
        return false;
      }
    }

    return true;
  }

  public function isTestExist($testHash)
  {
    $test = Test::where('hash', $testHash)->first();
    if(is_null($test)) return false;
    return true;
  }

  public function questionsSaver($questions)
  {
    foreach ($questions as $question) {
      $question = json_decode(json_encode($question), FALSE);
      $questionWhere = Question::findOrFail($question->id);
      $questionWhere->question = $question->name;
      $questionWhere->type_answer = $question->typeAnswer;
      $questionWhere->is_require = $question->isRequire;
      $questionWhere->video_link = $question->videoLink;
      $questionWhere->right_variants = json_encode($question->right_variants);
      $questionWhere->variants = json_encode($question->selectedVariants);
      $questionWhere->index = $question->index;
      $questionWhere->save();
    }
  }

  public function createSetting($test_id)
  {
    TestSetting::create([
      'test_id' => $test_id,
    ]);
  }

  public function createTest($request)
  {
    $test = new Test();
    $test->email = $this->email;
    $test->ip = $request['fingerprint'];
    $test->status = 'draft';
    $test->hash = sha1(uniqid($test->id, true));
    $test->save();

    $this->createSetting($test->id);

    return $test;
  }



  public function deleteTest($request)
  {
    $test = Test::findOrFail($request['id']);

    $test->clearMediaCollection();

    $questions = $test->questions;
    foreach ($questions as $question) {
      $question->clearMediaCollection('questionImage');
    }

    $test->delete();
  }

  public function saveTest($request, $test)
  {
    $test->name = $request->testName;
    $test->description = $request->testDescription;
    $test->video_link = $request->videoLink;
    $test->status = 'done';
    $test->save();

    $this->questionsSaver($request->questions);
    $this->saveSettings($request);
  }

  public function saveSettings($request)
  {
    $settings = TestSetting::where('test_id', $request->settings['test_id'])->first();
    $password = $settings->password;
    $settings->fill($request->settings);
    $settings->password = $password;

    if(strlen($request->settings['password']) > 0) {
      $settings->password = Hash::make($request->settings['password']);
    }

    $settings->save();
  }

  public function addMediaToTest($request, $test)
  {
    for ($i = 0; $i < $request->countImages; $i++) {
      if (
        $media = $test->addMediaFromRequest("testImage{$i}")
            ->usingFileName(rand() . $i . '.' . $request["imageType{$i}"])
            ->addCustomHeaders(
              ['ACL' => 'public-read']
            )
            ->toMediaCollection('testImage', 's3')
      ) {
        $id = $media->id;
        $mediaOption = new ImageOption();
        $mediaOption->alignment = 'left';
        $mediaOption->media_id = $id;
        $mediaOption->save();
      }
    }
  }

  public function getDispatche($request, $test)
  {
    $email = $this->email;

    $dispatche = DispatchesTest::where('test_id', $test->id)
        ->where(function($query) use ($email, $request) {
          $query->where('fingerprint', $request['fingerprint']);
          $query->orWhere('email', $email);
        })->first();

    return $dispatche;
  }

  public function changeImageSize($request)
  {
    $mediaOption = ImageOption::where('media_id', $request['id'])->first();
    $mediaOption->width = $request['width'];
    $mediaOption->height = $request['height'];
    $mediaOption->save();
  }
}
