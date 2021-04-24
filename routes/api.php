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

Route::post('test/create', 'TestController@createTest');
Route::post('test/save', 'TestController@saveTest');
Route::get('test/delete/{id}', 'TestController@deleteTest');

Route::post('test/question', 'QuestionController@create');
Route::get('test/question/delete/{id}', 'QuestionController@delete');
Route::post('test/question/upload', 'QuestionController@uploadImage');
Route::post('test/question/upload/delete', 'QuestionController@deleteImage');

Route::get('test/{id}', 'TestController@getTest');
Route::get('test/get/all', 'TestController@getTestAll');
Route::get('test/getByHash/{hash}', 'TestController@getTestByHash');
Route::get('test/questions/{id}', 'TestController@getQuestions');
Route::get('test/questions/getByHash/{hash}', 'TestController@getQuestionsByHash');
Route::get('stats/{hash}', 'StatsController@getStatsByHash');
Route::post('test/upload', 'TestController@uploadImage');
Route::post('test/upload/delete', 'TestController@deleteImage');
Route::post('test/checkIp', 'TestController@checkIp');

Route::post('poll/save', 'PollController@savePoll');
Route::post('poll/create', 'PollController@createPoll');
Route::get('poll/delete/{id}', 'PollController@deletePoll');
Route::get('poll/{id}', 'PollController@getPoll');
Route::get('polls/get/all', 'PollController@getPollAll');
Route::get('poll/getByHash/{hash}', 'PollController@getPollByHash');
Route::post('poll/upload', 'PollController@uploadImage');
Route::post('poll/upload/delete', 'PollController@deleteImage');
Route::post('poll/checkIp', 'TestController@checkIp');

Route::post('answers/send', 'AnswerController@store');
Route::get('answer/{id}', 'AnswerController@getAnswers');
Route::post('pollAnswers/send', 'PollAnswerController@store');
Route::get('pollAnswers/{id}', 'PollAnswerController@getAnswers');
