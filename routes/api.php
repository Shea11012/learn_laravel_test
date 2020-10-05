<?php

use Illuminate\Http\Request;

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
Route::group(['prefix' => 'v1'],function () {
    Route::get('questions','QuestionsController@index');
    Route::get('/questions/{question}','QuestionsController@show');

    Route::group(['middleware' => 'auth:api'],function () {
        Route::post('/questions/{question}/answers','AnswersController@store')->name('answers.store');

        Route::post('/answers/{answer}/best','BestAnswersController@store')->name('best-answers.store');
    });
});
