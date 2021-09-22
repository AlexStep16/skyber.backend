<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use App\Services\Registers\RegisterModel;

use App\Http\Requests\Register\{
  RegisterInvokeRequest, RegisterRestorePasswordRequest,
  RegisterChangePasswordRequest,
};

class RegisterController extends Controller
{
  public function __construct(RegisterModel $registerModel)
  {
    $this->registerModel = $registerModel;
  }

  public function __invoke(RegisterInvokeRequest $request)
  {
    $validatedRequest = $request->validated();

    $user = User::where('email', $validatedRequest['email'])->first();
    if (!is_null($user)) return response('Email exist', 402);

    $this->registerModel->register($validatedRequest);
  }

  public function restorePassword(RegisterRestorePasswordRequest $request)
  {
    $validatedRequest = $request->validated();

    $user = User::where('email', $validatedRequest['email'])->first();
    if (!is_null($user)) {
      $this->registerModel->restorePassword($validatedRequest);
    } else {
      return response('Fail', 500);
    }
  }

  public function changePassword(RegisterChangePasswordRequest $request)
  {
    $validatedRequest = $request->validated();

    $this->registerModel->changePassword($validatedRequest);
  }
}
