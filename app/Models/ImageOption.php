<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageOption extends Model
{
    use HasFactory;

    protected $fillable = [
      'alignment',
      'media_id',
    ];
}
