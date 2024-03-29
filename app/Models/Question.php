<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\File;

class Question extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
      'test_id',
      'question',
      'variants',
      'type_answer',
      'video_link'
    ];

    public function conditions() {
      return $this->hasMany('App\Models\ScenarioCondition');
    }
}
