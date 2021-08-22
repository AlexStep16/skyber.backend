<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scenario;
use App\Models\ScenarioCondition;
use App\Models\Test;
use App\Models\ImageOption;
use App\Http\Resources\ScenarioResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class ScenarioController extends Controller
{
  public function create(Request $request) {
    $scenarioData = json_decode($request->scenario, false);
    $testId = Test::where('hash', $scenarioData->testHash)->first()->id;

    $scenario = Scenario::create([
      "test_id" => $testId,
      "name" => $scenarioData->name ?? '',
      "header" => $scenarioData->header ?? '',
      "description" => $scenarioData->description ?? '',
    ]);

    return new ScenarioResource($scenario);
  }

  public function edit(Request $request) {
    $scenario = Scenario::findOrFail($request->id);
    $scenario->update([
      "name" => $request->name,
      "header" => $request->header,
      "description" => $request->description,
    ]);

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
    $hash = $request->hash;
    $scenario_id = $request->scenario_id;
    if(!is_null($hash)) {
      $test = Test::where('hash', $hash)->first();
    }
    else {
      $scenario = Scenario::findOrFail($scenario_id);
      if(is_null($scenario)) return response('Not Found', 400);
      $test = Test::findOrFail($scenario->test_id);
    }

    if(empty($test)) return response('Not Found', 400);

    if(($request->user() && $email == $test->email) || $request->fingerprint == $test->ip)
      return response('Ok', 200);
    else return response('Access denied', 401);
  }

  public function uploadImage(Request $request) {
    $scenario = Scenario::findOrFail($request->scenarioId);
    if($scenario == null) return response('Not Found', 400);

    for($i = 0; $i < $request->countImages; $i++) {
      if (
        $media = $scenario->addMediaFromRequest("scenarioImage{$i}")
             ->usingFileName(rand() . $i . '.' . $request["imageType{$i}"])
             ->addCustomHeaders([
               'ACL' => 'public-read'
             ])
             ->toMediaCollection('scenarioImage', 's3')
      ) {
        $id = $media->id;
        $mediaOption = new ImageOption();
        $mediaOption->alignment = 'left';
        $mediaOption->media_id = $id;
        $mediaOption->save();
      }
    }

    return new ScenarioResource($scenario);
  }

  public function deleteImage(Request $request) {
    $scenario = Scenario::findOrFail($request->scenarioId);
    if($scenario == null) return response('Not Found', 400);

    Media::findOrFail($request->id)->delete();
  }

  public function changeImageAlign(Request $request) {
    $mediaOption = ImageOption::where('media_id', $request->media_id)->first();
    $mediaOption->alignment = $request->align;
    $mediaOption->save();
  }
}
