<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scenario;
use App\Models\ScenarioCondition;
use App\Models\Test;
use App\Http\Resources\ScenarioResource;

class ScenarioController extends Controller
{
  public function create(Request $request) {
    $scenarioData = json_decode($request->scenario, false);
    $testId = Test::where('hash', $scenarioData->testHash)->first()->id;

    $scenario = Scenario::firstOrCreate([
      "test_id" => $testId,
      "name" => $scenarioData->name,
      "header" => $scenarioData->header,
      "description" => $scenarioData->description,
    ]);
    if(!$scenario->wasRecentlyCreated ) return null;
    if($scenario == null) return response('Failed', 404);
    if ($request->scenaImage != null) {
      $scenario->addMediaFromRequest('scenaImage')->toMediaCollection('scenarioImages');
      $image = $scenario->getMedia('scenarioImages')->first()->getFullUrl();
    } else {
      $image = null;
    }
    return response()->json(compact('image'));
  }

  public function edit(Request $request) {
    $scenarioData = json_decode($request->scenario, false);
    $scenario = Scenario::findOrFail($scenarioData->id);
    $scenario->update([
      "name" => $scenarioData->name,
      "header" => $scenarioData->header,
      "description" => $scenarioData->description,
    ]);

    if (
      !empty($scenarioData->image)
      && $scenario->getMedia('scenarioImages')->count() === 0
    ) {
      $scenario->clearMediaCollection('scenarioImages');
      $scenario->addMediaFromRequest('scenaImage')->toMediaCollection('scenarioImages');
    }
    else if(empty($scenarioData->image)) {
      $scenario->clearMediaCollection('scenarioImages');
    }

  }

  public function delete($id) {
    $scenario = Scenario::findOrFail($id);
    $scenario->clearMediaCollection();
    $scenario->delete();
  }

  public function getByTestHash($testHash) {
    $test = Test::where('hash', $testHash)->first();
    return ScenarioResource::collection($test->scenarios);
  }

  public function get($id) {
    return new ScenarioResource(Scenario::findOrFail($id));
  }

  public function saveConditions(Request $request) {
    $scenarios = json_decode(json_encode($request->scenarios), false);

    foreach($scenarios as $scenario) {
      foreach($scenario->conditions as $condition) {
        $scenarioModel = ScenarioCondition::where('scenario_id', $scenario->id)->where('condition', $condition->condition)->first();

        if($scenarioModel != null && !$condition->checked) {
          $scenarioModel->delete();
          continue;
        }
        if($scenarioModel == null && !$condition->checked) continue;
        $cond = $scenarioModel ? $scenarioModel : new ScenarioCondition();
        $cond->scores = $condition->scores;
        $cond->condition = $condition->condition;
        $cond->scenario_id = $scenario->id;
        $cond->save();
      }
    }
  }

  public function isScenarioAccess(Request $request) {
    $email = $request->user() ? $request->user()->email : '';
    $test = Test::where('hash', $request->hash)->first();

    if($test == null) return response('Not Found', 400);

    if(($request->user() && $email == $test->email) || $request->fingerprint == $test->ip)
      return response('Ok', 200);
    else return response('Access denied', 401);
  }
}
