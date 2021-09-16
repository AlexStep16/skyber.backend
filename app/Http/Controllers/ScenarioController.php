<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scenario;
use App\Services\Scenarios\ScenarioModel;
use App\Models\ScenarioCondition;
use App\Models\Test;
use App\Services\Tests\TestModel;
use App\Models\ImageOption;
use App\Http\Resources\ScenarioResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ScenarioController extends Controller
{
  public function __construct(TestModel $testModel, ScenarioModel $scenarioModel)
  {
    $this->testModel = $testModel;
    $this->scenarioModel = $scenarioModel;
  }

  public function create(Request $request)
  {
    $scenarioData = json_decode($request->scenario, false);

    if(!$this->testModel->isTestExist($scenarioData->testHash)) return response('Not Found', 400);

    $this->scenarioModel->create(
      Test::where('hash', $scenarioData->testHash)->first()->id,
      $scenarioData
    );

    return new ScenarioResource($scenario);
  }

  public function edit(Request $request)
  {
    $scenario = Scenario::findOrFail($request->id);

    $this->scenarioModel->update($request, $scenario);
  }

  public function delete($id)
  {
    $scenario = Scenario::findOrFail($id);

    $scenario->clearMediaCollection();
    $scenario->delete();
  }

  public function getByTestHash($testHash)
  {
    $test = Test::where('hash', $testHash)->first();

    if(!$this->testModel->isTestExist($testHash)) return response('Not Found', 400);

    return ScenarioResource::collection($test->scenarios);
  }

  public function get($id)
  {
    return new ScenarioResource(Scenario::find($id));
  }

  public function saveConditions(Request $request)
  {
    $this->scenarioModel->saveConditions($request);
  }

  public function isScenarioAccess(Request $request)
  {
    $scenario = Scenario::find($request->scenario_id);
    if(is_null($scenario)) return response('Not Found', 400);

    $test = Test::find($scenario->test_id);

    if (is_null($test)) return response('Not Found', 400);
    if (!$this->testModel->isMyTest($request, $test)) return response('Access denied', 401);

    return response('Ok', 200);
  }

  public function uploadImage(Request $request)
  {
    $scenario = Scenario::findOrFail($request->scenarioId);
    if (is_null($scenario)) return response('Not Found', 400);

    $this->scenarioModel->addMediaToScenario($request);

    return new ScenarioResource($scenario);
  }

  public function deleteImage(Request $request)
  {
    $scenario = Scenario::findOrFail($request->scenarioId);
    if (is_null($scenario)) return response('Not Found', 400);

    Media::findOrFail($request->id)->delete();
  }

  public function changeImageAlign(Request $request)
  {
    $mediaOption = ImageOption::where('media_id', $request->media_id)->first();
    $mediaOption->alignment = $request->align;
    $mediaOption->save();
  }
}
