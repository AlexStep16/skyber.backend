<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecoveryPasswordMail;
use App\Models\User;
use App\Models\Token;
use Carbon\Carbon;

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
        $timestamp = Carbon::now()->timestamp;
        $hash = Hash::make($request->email . $timestamp);
        $tokenModel = Token::where('email', $request->email)->first();
        if(!is_null($tokenModel)) {
          if($tokenModel->wasActivated === false) {
            $token = $tokenModel->token;
          }
          else {
            $token = Token::create(['token' => $hash, 'email' => $request->email, 'wasActivated' => false]);
          }
        }
        else {
          $token = Token::create(['token' => $hash, 'email' => $request->email, 'wasActivated' => false]);
        }
        if($token) {
          Mail::to($request->email)->send(new RecoveryPasswordMail($hash));
          return response('Success', 200);
        }
        else {
          return response('Fail', 500);
        }
      }
      else {
        return response('Fail', 500);
      }
    }

    public function changePassword(Request $request) {
      $tokenModel = Token::where('token', $request->hash)->first();
      if(!is_null($tokenModel)) $email = $tokenModel->email;
      else $email = null;
      $model = User::where('email', $email)->first();
      if($model === null) {
        return response("Fail $tokenModel", 500);
      }

      $model->password = Hash::make($request->password);
      $model->save();
    }
}
