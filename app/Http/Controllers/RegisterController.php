<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\Registers\RegisterModel;

class RegisterController extends Controller
{
  public function __construct(RegisterModel $registerModel)
  {
    $this->registerModel = $registerModel;
  }

  public function __invoke(Request $request)
  {
    $user = User::where('email', $request->email)->first();
    if(!is_null($user)) return response('Email exist', 402);

    $this->registerModel->register($request);
  }

  public function restorePassword(Request $request)
  {
    $user = User::where('email', $request->email)->first();
    if (!is_null($user)) {
      $this->registerModel->restorePassword($request);
    } else {
      return response('Fail', 500);
    }
  }

  public function changePassword(Request $request)
  {
    $this->registerModel->changePassword($request);
  }
}
