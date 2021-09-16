<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Http\Resources\TestResource;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\TestSettingResource;
use App\Models\DispatchesTest;
use App\Models\TestSetting;
use App\Models\ImageOption;
use App\Services\Tests\TestModel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    public function __construct(TestModel $testModel)
    {
      $this->testModel = $testModel;
    }

    public function createTest(Request $request): TestResource
    {
      return new TestResource($this->testModel->createTest($request));
    }

    public function deleteTest(Request $request)
    {
      $this->testModel->deleteTest($request);
    }

    public function saveTest(Request $request)
    {
      $test = Test::where('hash', $request->testHash)->first();

      if(!$this->testModel->isMyTest($request, $test)) {
        return response('Not identified', 401);
      }

      $this->testModel->saveTest($request, $test);
    }

    public function getTest(Request $request)
    {
      if(!$this->testModel->isTestExist($request->hash)) {
        return response('Not Found', 400);
      } else {
        $test = Test::where('hash', $request->hash)->first();
      }
      if(!$this->testModel->isMyTest($request, $test)) return response('Not identified', 401);

      return new TestResource($test);
    }

    public function getTestByHash($hash)
    {
      if(!$this->testModel->isTestExist($hash)) return response('Not Found', 400);

      return new TestResource(Test::where('hash', $hash)->first());
    }

    public function getTestAll(Request $request)
    {
      $email = $request->user() ? $request->user()->email  : 'undefined';

      $tests = Test::where('email', $email)
                   ->orWhere('ip', $request->fingerprint)
                   ->orderByDesc('created_at')
                   ->get();

      return TestResource::collection($tests);
    }

    public function getQuestions(Request $request)
    {
      if ($this->testModel->isTestExist($request->hash)) {
        $test = Test::where('hash', $request->hash)->first();
        $questions = $test->questions;
      } else return response('Not Found', 400);

      if (!$this->testModel->isMyTest($request, $test)) {
        return response('Not identified', 401);
      }
      return QuestionResource::collection($questions);
    }

    public function getQuestionsByHash($hash)
    {
      if(!$this->testModel->isTestExist($hash)) return response('Not Found', 400);
      else $test = Test::where('hash', $hash)->first();

      return QuestionResource::collection($test->questions);
    }

    public function uploadImage(Request $request)
    {
      if(!$this->testModel->isTestExist($hash)) return response('Not Found', 400);
      else $test = Test::where('hash', $request->testHash)->first();

      $this->testModel->addMediaToTest($request, $test);

      return new TestResource($test);
    }

    public function changeImageAlign(Request $request)
    {
      $mediaOption = ImageOption::where('media_id', $request->media_id)->first();
      $mediaOption->alignment = $request->align;
      $mediaOption->save();
    }

    public function changeImageSize(Request $request)
    {
      $mediaOption = ImageOption::where('media_id', $request->id)->first();
      $mediaOption->width = $request->width;
      $mediaOption->height = $request->height;
      $mediaOption->save();
    }

    public function deleteImage(Request $request)
    {
      if(!$this->testModel->isTestExist($request->testHash)) return response('Not Found', 400);

      Media::findOrFail($request->id)->delete();
    }

    public function checkDispatch(Request $request)
    {
      if(!$this->testModel->isTestExist($request->hash)) return response('Not Found', 404);
      else $test = Test::where('hash', $request->hash)->first();

      $dispatche = $this->testModel->getDispatche($request, $test);

      return $dispatche;
    }

    public function checkPassword(Request $request) {
      $test = Test::where('hash', $request->test_hash)->first();
      $settings = $test->settings->first();
      if(Hash::check($request->password, $settings->password)) {
        return 'verified';
      };
      return 'not verified';
    }
}
