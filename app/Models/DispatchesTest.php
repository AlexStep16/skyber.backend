<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchesTest extends Model
{
    use HasFactory;

    protected $fillable = [
      'email',
      'test_id',
      'fingerprint',
    ];
}
