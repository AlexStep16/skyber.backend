<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\File;

class Poll extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
      'name',
      'description',
      'email',
      'status',
      'variants',
      'hash',
      'count_sub',
      'type_variants'
    ];

    public static function boot() {
      parent::boot();

      static::deleting(function($user) {
        $user->dispatches()->delete();
      });
    }

    public function dispatches() {
      return $this->hasMany('App\Models\DispatchesPoll');
    }
}
