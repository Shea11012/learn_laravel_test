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
    Route::get('questions','QuestionsController@index')->name('questions.list');
    Route::get('/questions/{question}','QuestionsController@show')->name('questions.show');

    Route::group(['middleware' => 'auth:api'],function () {
        Route::post('/questions/{question}/answers','AnswersController@store')->name('answers.store');
        Route::delete('/answers/{answer}','AnswersController@destroy')->name('answers.destroy');

        Route::post('/answers/{answer}/best','BestAnswersController@store')->name('best-answers.store');

        Route::post('/answers/{answer}/up-votes','AnswersUpVotesController@store')->name('answer-up-votes.store');
        Route::delete('/answers/{answer}/up-votes','AnswersUpVotesController@destroy')->name('answer-up-votes.destroy');

        Route::post('/answers/{answer}/down-votes','AnswerDownVotesController@store')->name('answer-down-votes.store');
        Route::delete('/answers/{answer}/down-votes','AnswerDownVotesController@destroy')->name('answer-down-votes.destroy');
    });
});
