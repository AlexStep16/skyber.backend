<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function create(Request $request) {
      for($i = 0; $i < count($request->array); $i++) {
        $question = new Question();
        $question->question = $request->array[$i]['name'];
        $question->testId = $request->array[$i]['testId'];
        $question->save();
      }
    }
}
