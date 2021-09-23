<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

use App\Models\Test;
use App\Models\Question;
use App\Models\DispatchesTest;
use App\Models\TestSetting;
use App\Models\ImageOption;

use App\Http\Resources\TestResource;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\TestSettingResource;

use App\Services\Tests\TestModel;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Http\Requests\Tests\{
  TestCreateRequest, TestDeleteRequest, TestChangeImageAlignRequest,
  TestChangeImageSizeRequest, TestCheckDispatchRequest, TestCheckPasswordRequest,
  TestDeleteImageRequest, TestGetAllRequest, TestGetRequest,
  TestGetQuestionRequest, TestSaveRequest
};

class TestController extends Controller
{
    /**
     * Undocumented function
     *
     * @param TestModel $testModel
     */
    public function __construct(TestModel $testModel)
    {
      $this->testModel = $testModel;
    }

    /**
     * Create test with settings
     *
     * @param TestCreateRequest $request
     * @return TestResource
     */
    public function createTest(TestCreateRequest $request): TestResource
    {
      $validatedRequest = $request->validated();

      return new TestResource($this->testModel->createTest($validatedRequest));
    }

    /**
     * Delete test with clearing media collections
     *
     * @param TestDeleteRequest $request
     * @return void
     */
    public function deleteTest(TestDeleteRequest $request)
    {
      $validatedRequest = $request->validated();

      if (!$this->testModel->isMyTest($validatedRequest, Test::findOrFail($validatedRequest['id']))) {
        return response("It's Not Your", 401);
      }
      $this->testModel->deleteTest($validatedRequest);
    }

    /**
     * Save test
     *
     * @param TestSaveRequest $request
     * @return void
     */
    public function saveTest(TestSaveRequest $request)
    {
      $validatedRequest = $request->validated();

      $test = Test::where('hash', $validatedRequest['testHash'])->first();

      if(!$this->testModel->isMyTest($validatedRequest, $test)) {
        return response('Not identified', 401);
      }

      $this->testModel->saveTest($request, $test);
    }

    /**
     * Returning test for make the test(with auth check)
     *
     * @param TestGetRequest $request
     * @return TestResource
     */
    public function getTest(TestGetRequest $request): TestResource
    {
      $validatedRequest = $request->validated();

      if(!$this->testModel->isTestExist($validatedRequest['hash'])) {
        return response('Not Found', 400);
      } else {
        $test = Test::where('hash', $validatedRequest['hash'])->first();
      }
      if(!$this->testModel->isMyTest($request, $test)) return response('Not identified', 401);

      return new TestResource($test);
    }

    /**
     * Undocumented function
     *
     * @param String $hash
     * @return TestResource
     */
    public function getTestByHash(String $hash): TestResource
    {
      if(!$this->testModel->isTestExist($hash)) return response('Not Found', 400);

      return new TestResource(Test::where('hash', $hash)->first());
    }

    /**
     * Undocumented function
     *
     * @param TestGetAllRequest $request
     * @return AnonymousResourceCollection
     */
    public function getTestAll(TestGetAllRequest $request): AnonymousResourceCollection
    {
      $validatedRequest = $request->validated();

      $tests = Test::where('email', $this->testModel->email)
                   ->orWhere('ip', $validatedRequest['fingerprint'])
                   ->orderByDesc('created_at')
                   ->get();

      return TestResource::collection($tests);
    }

    /**
     * Undocumented function
     *
     * @param TestGetQuestionRequest $request
     * @return AnonymousResourceCollection
     */
    public function getQuestions(TestGetQuestionRequest $request): AnonymousResourceCollection
    {
      $validatedRequest = $request->validated();

      if ($this->testModel->isTestExist($validatedRequest['hash'])) {
        $test = Test::where('hash', $validatedRequest['hash'])->first();
        $questions = $test->questions;
      } else return response('Not Found', 400);

      if (!$this->testModel->isMyTest($validatedRequest, $test)) {
        return response('Not identified', 401);
      }
      return QuestionResource::collection($questions);
    }

    /**
     * Undocumented function
     *
     * @param String $hash
     * @return AnonymousResourceCollection
     */
    public function getQuestionsByHash(String $hash): AnonymousResourceCollection
    {
      if(!$this->testModel->isTestExist($hash)) return response('Not Found', 400);
      else $test = Test::where('hash', $hash)->first();

      return QuestionResource::collection($test->questions);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return TestResource
     */
    public function uploadImage(Request $request): TestResource
    {
      if(!$this->testModel->isTestExist($request['testHash'])) return response('Not Found', 400);
      else $test = Test::where('hash', $request['testHash'])->first();

      $this->testModel->addMediaToTest($request, $test);

      return new TestResource($test);
    }

    /**
     * Undocumented function
     *
     * @param TestChangeImageAlignRequest $request
     * @return void
     */
    public function changeImageAlign(TestChangeImageAlignRequest $request)
    {
      $validatedRequest = $request->validated();

      $mediaOption = ImageOption::where('media_id', $validatedRequest['media_id'])->first();
      $mediaOption->alignment = $validatedRequest['align'];
      $mediaOption->save();
    }

    /**
     * Undocumented function
     *
     * @param TestChangeImageSizeRequest $request
     * @return void
     */
    public function changeImageSize(TestChangeImageSizeRequest $request)
    {
      $validatedRequest = $request->validated();

      $this->testModel->changeImageSize($validatedRequest);
    }

    /**
     * Undocumented function
     *
     * @param TestDeleteImageRequest $request
     * @return void
     */
    public function deleteImage(TestDeleteImageRequest $request)
    {
      $validatedRequest = $request->validated();

      if(!$this->testModel->isTestExist($validatedRequest['testHash'])) return response('Not Found', 400);

      Media::findOrFail($validatedRequest['id'])->delete();
    }

    /**
     * Undocumented function
     *
     * @param TestCheckDispatchRequest $request
     * @return void
     */
    public function checkDispatch(TestCheckDispatchRequest $request)
    {
      $validatedRequest = $request->validated();

      if (!$this->testModel->isTestExist($validatedRequest['hash'])) {
        return response('Not Found', 404);
      } else {
        $test = Test::where('hash', $validatedRequest['hash'])->first();
      }

      $dispatche = $this->testModel->getDispatche($validatedRequest, $test);

      return $dispatche;
    }

    /**
     * Undocumented function
     *
     * @param TestCheckPasswordRequest $request
     * @return String
     */
    public function checkPassword(TestCheckPasswordRequest $request): String {
      $validatedRequest = $request->validated();

      $test = Test::where('hash', $validatedRequest['test_hash'])->first();
      $settings = $test->settings->first();

      if (Hash::check($validatedRequest['password'], $settings->password)) {
        return 'verified';
      };

      return 'not verified';
    }
}
