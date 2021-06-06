<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\File;

class Scenario extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public static function boot() {
      parent::boot();

      static::deleting(function($user) {
        $user->conditions()->delete();
      });
    }

    protected $fillable = [
      'test_id',
      'name',
      'header',
      'description',
    ];

    public function conditions() {
      return $this->hasMany('App\Models\ScenarioCondition');
    }

    public function test() {
      return $this->belongsTo('App\Models\Test');
    }
}
