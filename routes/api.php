<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

  'prefix' => 'auth',
  'namespace' => 'Auth'

], function ($router) {

  Route::post('login', 'SignInController');
  Route::post('logout', 'SignOutController');
  Route::get('me', 'MeController');

});

Route::post('test/create', 'TaPController@createTest');
Route::post('test/save', 'TaPController@saveTest');
Route::post('poll/create', 'TaPController@createPoll');

Route::post('register', 'RegisterController');

Route::post('test/question', 'QuestionController@create');
Route::get('test/question/delete/{id}', 'QuestionController@delete');

Route::get('test/{id}', 'TaPController@getTest');
Route::get('test/questions/{id}', 'TaPController@getQuestions');

