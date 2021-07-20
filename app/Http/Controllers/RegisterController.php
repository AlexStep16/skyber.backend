<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke(Request $request) {
      $model = User::where('email', $request->email)->first();
      if($model != null) {
        return response('Email exist', 402);
      }

      $user = new User;
      $user->email = $request->email;
      $user->password = Hash::make($request->password);
      $user->hash = md5($user->email . $user->id);
      $user->save();
    }

    public function restorePassword(Request $request) {
      $model = User::where('email', $request->email)->first();
      if($model !== null) {
        return response('Success', 200);
      }
      else {
        return response('Fail', 404);
      }
    }

    public function changePassword(Request $request) {
      $model = User::where('hash', $request->hash)->first();
      if($model === null) {
        return response('Fail', 404);
      }

      $model->password = Hash::make($request->password);
      $model->save();
    }
}
