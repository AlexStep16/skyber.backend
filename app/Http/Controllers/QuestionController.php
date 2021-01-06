<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
  public function create(Request $request) {
    $question = new Question();
    $question->test_id = $request->testId;
    $question->radio_variants = json_encode($request->radioVariant);
    $question->question = $request->name;
    $question->save();
    return $question->id;
  }

  public function delete($id) {
    Question::findOrFail($id)->delete();
  }
}
