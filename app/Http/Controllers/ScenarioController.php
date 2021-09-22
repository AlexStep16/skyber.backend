<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Scenario;
use App\Models\ScenarioCondition;
use App\Models\Test;
use App\Models\ImageOption;

use App\Services\Scenarios\ScenarioModel;
use App\Services\Tests\TestModel;

use App\Http\Resources\ScenarioResource;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Http\Requests\Scenarios\{
  ScenarioCreateRequest, ScenarioEditRequest, ScenarioSaveConditionsRequest,
  ScenarioIsAccessRequest, ScenarioDeleteImageRequest, ScenarioChangeImageAlignRequest
};

class ScenarioController extends Controller
{
  /**
   * Undocumented function
   *
   * @param TestModel $testModel
   * @param ScenarioModel $scenarioModel
   */
  public function __construct(TestModel $testModel, ScenarioModel $scenarioModel)
  {
    $this->testModel = $testModel;
    $this->scenarioModel = $scenarioModel;
  }

  /**
   * Undocumented function
   *
   * @param ScenarioCreateRequest $request
   * @return ScenarioResource
   */
  public function create(ScenarioCreateRequest $request): ScenarioResource
  {
    $validatedRequest = $request->validated();

    $scenarioData = json_decode($validatedRequest['scenario'], false);

    if(!$this->testModel->isTestExist($scenarioData->testHash)) return response('Not Found', 400);

    $this->scenarioModel->create(
      Test::where('hash', $scenarioData->testHash)->first()->id,
      $scenarioData
    );

    return new ScenarioResource($scenario);
  }

  /**
   * Undocumented function
   *
   * @param ScenarioEditRequest $request
   * @return void
   */
  public function edit(ScenarioEditRequest $request)
  {
    $validatedRequest = $request->validated();

    $scenario = Scenario::findOrFail($validatedRequest['id']);

    $this->scenarioModel->update($validatedRequest, $scenario);
  }

  /**
   * Undocumented function
   *
   * @param Int $id
   * @return void
   */
  public function delete(Int $id)
  {
    $scenario = Scenario::findOrFail($id);

    $scenario->clearMediaCollection();
    $scenario->delete();
  }

  /**
   * Undocumented function
   *
   * @param String $testHash
   * @return ScenarioResource
   */
  public function getByTestHash(String $testHash): ScenarioResource
  {
    $test = Test::where('hash', $testHash)->first();

    if(!$this->testModel->isTestExist($testHash)) return response('Not Found', 400);

    return ScenarioResource::collection($test->scenarios);
  }

  /**
   * Undocumented function
   *
   * @param Int $id
   * @return ScenarioResource
   */
  public function get(Int $id): ScenarioResource
  {
    return new ScenarioResource(Scenario::find($id));
  }

  /**
   * Undocumented function
   *
   * @param ScenarioSaveConditionsRequest $request
   * @return void
   */
  public function saveConditions(ScenarioSaveConditionsRequest $request)
  {
    $validatedRequest = $request->validated();

    $this->scenarioModel->saveConditions($validatedRequest);
  }

  /**
   * Undocumented function
   *
   * @param ScenarioIsAccessRequest $request
   * @return boolean
   */
  public function isScenarioAccess(ScenarioIsAccessRequest $request)
  {
    $validatedRequest = $request->validated();

    $scenario = Scenario::find($validatedRequest['scenario_id']);
    if(is_null($scenario)) return response('Not Found', 400);

    $test = Test::find($scenario->test_id);

    if (is_null($test)) return response('Not Found', 400);
    if (!$this->testModel->isMyTest($request, $test)) return response('Access denied', 401);

    return response('Ok', 200);
  }

  /**
   * Undocumented function
   *
   * @param Request $request
   * @return ScenarioResource
   */
  public function uploadImage(Request $request): ScenarioResource
  {
    $scenario = Scenario::findOrFail($request->scenarioId);
    if (is_null($scenario)) return response('Not Found', 400);

    $this->scenarioModel->addMediaToScenario($request);

    return new ScenarioResource($scenario);
  }

  /**
   * Undocumented function
   *
   * @param ScenarioDeleteImageRequest $request
   * @return void
   */
  public function deleteImage(ScenarioDeleteImageRequest $request)
  {
    $validatedRequest = $request->validated();

    $scenario = Scenario::findOrFail($validatedRequest['scenarioId']);
    if (is_null($scenario)) return response('Not Found', 400);

    Media::findOrFail($validatedRequest['id'])->delete();
  }

  /**
   * Undocumented function
   *
   * @param ScenarioChangeImageAlignRequest $request
   * @return void
   */
  public function changeImageAlign(ScenarioChangeImageAlignRequest $request)
  {
    $validatedRequest = $request->validated();

    $mediaOption = ImageOption::where('media_id', $validatedRequest['media_id'])->first();
    $mediaOption->alignment = $validatedRequest['align'];
    $mediaOption->save();
  }
}
