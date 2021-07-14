<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSetting extends Model
{
    use HasFactory;

    protected $fillable = [
      'test_id',
      'access_for_all',
      'password_access',
      'is_list',
      'is_resend',
      'is_right_questions',
      'is_wrong_questions',
      'is_reanswer',
      'has_statistic',
      'password',
    ];
}
