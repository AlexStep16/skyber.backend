<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioCondition extends Model
{
    use HasFactory;

    protected $fillable = [
      'scenario_id',
      'condition',
      'scores',
      'question_id',
    ];

    public function question() {
      return $this->belongsTo('App\Models\Question');
    }
}
