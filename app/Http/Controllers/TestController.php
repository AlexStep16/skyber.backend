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
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    public function createTest(Request $request) {
      $test = new Test();
      $test->name = $request->name ?? null;
      $test->email = $request->user()->email ?? null;
      $test->ip = $request->fingerprint;
      $test->status = 'draft';
      $test->hash = sha1(uniqid($test->id, true));
      $test->save();

      TestSetting::create([
        'test_id' => $test->id,
      ]);

      return new TestResource(Test::findOrFail($test->id));
    }

    public function deleteTest(Request $request) {
      $email = $request->user() ? $request->user()->email : '';
      $test = Test::findOrFail($request->id);
      if($test->email != $email && $test->ip != $request->fingerprint && $test->ip != null) {
        return response("It's Not Your", 401);
      }
      $test->clearMediaCollection();

      $questions = $test->questions;
      foreach ($questions as $question) {
        $question->clearMediaCollection('questionImage');
      }

      $test->delete();
    }

    public function saveTest(Request $request) {
      $test = Test::where('hash', $request->testHash)->first();
      if(!$request->user() && $request->fingerprint !== $test->ip) {
        return response('Not identified', 401);
      }

      $test->name = $request->testName;
      $test->description = $request->testDescription;
      $test->video_link = $request->videoLink;
      $test->status = 'done';
      $test->save();
      $questions = $request->questions;

      foreach($questions as $question) {
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

      $settings = TestSetting::where('test_id', $request->settings['test_id'])->first();
      $password = $settings->password;
      $settings->fill($request->settings);
      $settings->password = $password;
      if(strlen($request->settings['password']) > 0) {
        $settings->password = Hash::make($request->settings['password']);
      }
      $settings->save();
    }

    public function getTest(Request $request) {
      $email = $request->user() ? $request->user()->email : '';
      $test = Test::where('hash', $request->hash)->first();
      if($test == null) return response('Not Found', 400);

      if($email === $test->email || $request->fingerprint === $test->ip)
        return new TestResource($test);
      else return response('Not identified', 401);
    }

    public function generate_code_rand() {
      $len = 5;
      $short = "";
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $charslen = strlen($chars);
      for ($i=0; $i<$len; $i++)
      {
              $rnd = rand(0, $charslen);
              $short .= substr($chars, $rnd, 1);
      }
      return $short;
    }

    public function getTestAll(Request $request) {
      $email = $request->user() ? $request->user()->email  : 'undefined';
      $tests = Test::where('email', $email)->orWhere('ip', $request->fingerprint)->orderByDesc('created_at')->get();

      return TestResource::collection($tests);
    }

    public function getQuestions(Request $request) {
      $test = Test::where('hash', $request->hash)->first();
      $email = $request->user() ? $request->user()->email : '';

      if($test != null) $questions = $test->questions;
      else return "";

      if($email === $test->email || $request->fingerprint === $test->ip)
        return QuestionResource::collection($questions);
      else return response('Not identified', 401);
    }

    public function uploadImage(Request $request) {
      $test = Test::where('hash', $request->testHash)->first();
      if($test == null) return response('Not Found', 400);

      for($i = 0; $i < $request->countImages; $i++) {
        if (
          $media = $test->addMediaFromRequest("testImage{$i}")
               ->usingFileName(rand() . $i . '.' . $request["imageType{$i}"])
->addCustomHeaders([
            'ACL' => 'public-read'
        ])
->toMediaCollection('testImage', 's3')
        ) {
          $id = $media->id;
          $mediaOption = new ImageOption();
          $mediaOption->alignment = 'left';
          $mediaOption->media_id = $id;
          $mediaOption->save();
        }
      }

      return new TestResource($test);
    }

    public function changeImageAlign(Request $request) {
      $mediaOption = ImageOption::where('media_id', $request->media_id)->first();
      $mediaOption->alignment = $request->align;
      $mediaOption->save();
    }

    public function changeImageSize(Request $request) {
      $mediaOption = ImageOption::where('media_id', $request->id)->first();
      $mediaOption->width = $request->width;
      $mediaOption->height = $request->height;
      $mediaOption->save();
    }

    public function deleteImage(Request $request) {
      $test = Test::where('hash', $request->testHash)->first();
      if($test == null) return response('Not Found', 400);

      Media::findOrFail($request->id)->delete();
    }

    public function checkDispatch(Request $request) {
      $email = $request->user() ? $request->user()->email  : '';
      $test = Test::where('hash', $request->hash)->first();
      $dispatche = DispatchesTest::where('test_id', $test->id)
        ->where(function($query) use($email, $request) {
          $query->where('fingerprint', $request->fingerprint);
          $query->orWhere('email', $email);
        })->first();
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
