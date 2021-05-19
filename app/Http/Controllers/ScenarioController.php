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

    if($scenario == null) return response('Failed', 404);

    if ($scenario->addMediaFromRequest('scenaImage')->toMediaCollection('scenarioImages')) {
      $image = $scenario->getMedia('scenarioImages')->first()->getFullUrl();
    } else {
      $image = null;
    }
    return response()->json(compact('image'));
  }

  public function getByTestHash($testHash) {
    $test = Test::where('hash', $testHash)->first();
    return ScenarioResource::collection($test->scenarios);
  }

  public function saveConditions(Request $request) {
    $scenarios = json_decode(json_encode($request->scenarios), false);

    foreach($scenarios as $scenario) {
      foreach($scenario->conditions as $condition) {
        if(!$condition->checked) continue;
        $cond = new ScenarioCondition();
        $cond->scores = $condition->scores;
        $cond->condition = '';
        $cond->scenario_id = $scenario->id;
        $cond->save();
      }
    }
  }
}
