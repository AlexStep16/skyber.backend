<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\File;

class Test extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
      'name',
      'description',
      'email',
      'status',
      'hash',
      'count_sub'
    ];

    public static function boot() {
      parent::boot();

      static::deleting(function($user) {
        $user->dispatches()->delete();
        $user->scenarios()->delete();
        $user->questions()->delete();
        $user->settings()->delete();
      });
    }

    public function questions()
    {
      return $this->hasMany('App\Models\Question')->orderBy('index');
    }

    public function dispatches() {
      return $this->hasMany('App\Models\DispatchesTest');
    }

    public function scenarios() {
      return $this->hasMany('App\Models\Scenario')->orderBy('created_at');
    }

    public function settings() {
      return $this->hasMany('App\Models\TestSetting');
    }
}
