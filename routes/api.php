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

Route::post('register', 'RegisterController');
Route::post('password/recovery', 'RegisterController@restorePassword');
Route::post('password/change', 'RegisterController@changePassword');

Route::post('test/create', 'TestController@createTest');
Route::post('test/save', 'TestController@saveTest');
Route::post('test/delete', 'TestController@deleteTest');

Route::post('test/question', 'QuestionController@create');
Route::get('test/question/delete/{id}', 'QuestionController@delete');
Route::post('test/question/upload', 'QuestionController@uploadImage');
Route::post('test/question/upload/delete', 'QuestionController@deleteImage');

Route::post('test', 'TestController@getTest');
Route::post('test/get/all', 'TestController@getTestAll');
Route::get('test/getByHash/{hash}', 'TestController@getTestByHash');
Route::post('test/questions', 'TestController@getQuestions');
Route::get('test/questions/getByHash/{hash}', 'TestController@getQuestionsByHash');
Route::post('stats', 'StatsController@getStatsByHash');
Route::post('test/upload', 'TestController@uploadImage');
Route::post('test/image/alignment', 'TestController@changeImageAlign');
Route::post('test/image/size', 'TestController@changeImageSize');
Route::post('test/upload/delete', 'TestController@deleteImage');
Route::post('test/dispatch/check', 'TestController@checkDispatch');

Route::post('poll/save', 'PollController@savePoll');
Route::post('poll/create', 'PollController@createPoll');
Route::post('poll/delete', 'PollController@deletePoll');
Route::post('poll/', 'PollController@getPoll');
Route::get('polls/get/all', 'PollController@getPollAll');
Route::get('poll/getByHash/{hash}', 'PollController@getPollByHash');
Route::post('poll/upload', 'PollController@uploadImage');
Route::post('poll/upload/delete', 'PollController@deleteImage');
Route::post('poll/dispatch/check', 'PollController@checkDispatch');

Route::post('scenarios/create', 'ScenarioController@create');
Route::get('scenarios/{testHash}', 'ScenarioController@getByTestHash');
Route::get('scenario/{id}', 'ScenarioController@get');
Route::post('scenario/edit/{id}', 'ScenarioController@edit');
Route::post('scenario/upload', 'ScenarioController@uploadImage');
Route::post('scenario/upload/delete', 'ScenarioController@deleteImage');
Route::post('scenario/image/alignment', 'ScenarioController@changeImageAlign');
Route::delete('scenario/delete/{id}', 'ScenarioController@delete');
Route::post('scenarios/conditions/save', 'ScenarioController@saveConditions');
Route::post('scenarios/check/access', 'ScenarioController@isScenarioAccess');

Route::post('settings/save', 'SettingsController@save');

Route::post('answers/send', 'AnswerController@store');
Route::get('answer/{id}', 'AnswerController@getAnswers');
Route::post('pollAnswers/send', 'PollAnswerController@store');
Route::get('pollAnswers/{id}', 'PollAnswerController@getAnswers');

Route::post('password-closed', 'TestController@checkPassword');
