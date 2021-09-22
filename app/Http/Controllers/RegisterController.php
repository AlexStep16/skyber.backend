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
  /**
   * Undocumented function
   *
   * @param RegisterModel $registerModel
   */
  public function __construct(RegisterModel $registerModel)
  {
    $this->registerModel = $registerModel;
  }

  /**
   * Undocumented function
   *
   * @param RegisterInvokeRequest $request
   * @return void
   */
  public function __invoke(RegisterInvokeRequest $request)
  {
    $validatedRequest = $request->validated();

    $user = User::where('email', $validatedRequest['email'])->first();
    if (!is_null($user)) return response('Email exist', 402);

    $this->registerModel->register($validatedRequest);
  }

  /**
   * Undocumented function
   *
   * @param RegisterRestorePasswordRequest $request
   * @return void
   */
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

  /**
   * Undocumented function
   *
   * @param RegisterChangePasswordRequest $request
   * @return void
   */
  public function changePassword(RegisterChangePasswordRequest $request)
  {
    $validatedRequest = $request->validated();

    $this->registerModel->changePassword($validatedRequest);
  }
}
