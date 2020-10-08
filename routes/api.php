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
Route::group(['prefix' => 'v1'], function () {
    // question list
    Route::get('/questions/{question}', 'QuestionsController@show')->name('questions.show')->where(['question' => '\d+']);
    Route::get('/questions/{category?}', 'QuestionsController@index')->name('questions.list')->where(['category' => '[\w-]+']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/questions/{question}/subscriptions','SubscribeQuestionsController@store')->name('subscribe-questions.store');
        Route::delete('/questions/{question}/subscriptions','SubscribeQuestionsController@destroy')->name('subscribe-questions.destroy');
        // answers votes
        Route::post('/questions/{question}/answers', 'AnswersController@store')->name('answers.store');
        Route::delete('/answers/{answer}', 'AnswersController@destroy')->name('answers.destroy');

        Route::post('/answers/{answer}/best', 'BestAnswersController@store')->name('best-answers.store');

        Route::post('/answers/{answer}/up-votes', 'AnswersUpVotesController@store')->name('answer-up-votes.store');
        Route::delete('/answers/{answer}/up-votes', 'AnswersUpVotesController@destroy')->name('answer-up-votes.destroy');

        Route::post('/answers/{answer}/down-votes', 'AnswerDownVotesController@store')->name('answer-down-votes.store');
        Route::delete('/answers/{answer}/down-votes', 'AnswerDownVotesController@destroy')->name('answer-down-votes.destroy');

        // question votes
        Route::post('/questions/{question}/up-votes','QuestionUpVotesController@store')->name('question-up-votes.store');
        Route::delete('/questions/{question}/up-votes','QuestionUpVotesController@destroy')->name('question-up-votes.destroy');
        Route::post('/questions/{question}/down-votes','QuestionDownVotesController@store')->name('question-down-votes.store');
        Route::delete('/questions/{question}/down-votes','QuestionDownVotesController@destroy')->name('question-down-votes.destroy');

        // add question must verify email
        Route::group(['middleware' => ['must-verify-email'],], function () {
            Route::post('/questions', 'QuestionsController@store')->name('questions.store');
        });

        // publish question
        Route::post('/questions/{question}/published-questions', 'PublishedQuestionsController@store')->name('published-questions.store');
    });
});
