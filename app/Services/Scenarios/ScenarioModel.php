<?php

namespace App\Services\Scenarios;

use App\Models\Scenario;
use App\Models\ScenarioCondition;
use App\Models\ImageOption;

class ScenarioModel
{

  public function create($testId, $scenarioData) {
    Scenario::create([
      "test_id" => $testId,
      "name" => $scenarioData->name ?? '',
      "header" => $scenarioData->header ?? '',
      "description" => $scenarioData->description ?? '',
    ]);
  }

  public function update($request, $scenario) {
    $scenario->update([
      "name" => $request['name'],
      "header" => $request['header'],
      "description" => $request['description'],
    ]);
  }

  public function saveConditions($request)
  {
    $scenarios = json_decode(json_encode($request['scenarios']), false);

    foreach ($scenarios as $scenario) {
      foreach ($scenario->conditions as $condition) {
        $scenarioModel = ScenarioCondition::where('scenario_id', $scenario->id)
                                          ->where('condition', $condition->condition)
                                          ->first();

        if ($scenarioModel != null && !$condition->checked) {
          $scenarioModel->delete();
          continue;
        }
        if ($scenarioModel == null && !$condition->checked) continue;

        $cond = $scenarioModel ? $scenarioModel : new ScenarioCondition();
        $cond->scores = $condition->scores;
        $cond->condition = $condition->condition;
        $cond->scenario_id = $scenario->id;
        $cond->save();
      }
    }
  }

  public function addMediaToScenario($request)
  {
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
  }
}
