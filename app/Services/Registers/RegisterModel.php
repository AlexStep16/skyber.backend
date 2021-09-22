<?php

namespace App\Services\Registers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Token;
use App\Models\User;
use App\Mail\RecoveryPasswordMail;
use Carbon\Carbon;

class ReqisterModel
{
  public function register($request)
  {
    $user = new User;
    $user->email = $request['email'];
    $user->password = Hash::make($request['password']);
    $user->hash = md5($user->email . $user->id);
    $user->save();
  }

  public function changePassword($request)
  {
    $tokenModel = Token::where('token', $request['hash'])->first();

    if (!is_null($tokenModel) && !$tokenModel->wasActivated) {
      $email = $tokenModel->email;
      $model = User::where('email', $email)->first();
      if ($model === null) {
        return response("Fail $tokenModel", 500);
      }

      $tokenModel->wasActivated = true;
      $tokenModel->save();

      $model->password = Hash::make($request['password']);
      $model->save();
    }
  }

  public function restorePassword($request)
  {
    $timestamp = Carbon::now()->timestamp;
    $hash = md5(Hash::make($request['email'] . $timestamp));
    $tokenModel = Token::where('email', $request['email'])->where('wasActivated', false)->first();
    if (!is_null($tokenModel)) {
      if ($tokenModel->wasActivated === false) {
        $hash = $tokenModel->token;
      }
    }
    else {
      $token = Token::create(['token' => $hash, 'email' => $request['email'], 'wasActivated' => false]);
    }
    if (!is_null($tokenModel) || !is_null($token)) {
      Mail::to($request['email'])->send(new RecoveryPasswordMail($hash));
    }
  }
}
