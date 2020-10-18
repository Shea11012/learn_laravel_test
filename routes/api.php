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
    Route::get('/questions/{category}/{question}/{slug?}', 'QuestionsController@show')->name('questions.show')->where(['question' => '\d+','category' => '[\w-]+','slug' => '[\w-]+']);
    Route::get('/questions/{category?}', 'QuestionsController@index')->name('questions.list')->where(['category' => '[\w-]+']);
    Route::get('/questions/{question}/comments','QuestionCommentsController@index')->name('question-comments.index');
    Route::get('/answers/{answer}/comments','AnswerCommentsController@index')->name('answer-comments.index');


    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/questions/{question}/subscriptions','SubscribeQuestionsController@store')->name('subscribe-questions.store');
        Route::delete('/questions/{question}/subscriptions','SubscribeQuestionsController@destroy')->name('subscribe-questions.destroy');

        Route::post('/questions/{question}/comments','QuestionCommentsController@store')->name('question-comments.store');
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

        // comments
        Route::post('/questions/{question}/comments','QuestionCommentsController@store')->name('question-comments.store');

        Route::post('/answers/{answer}/comments','AnswerCommentsController@store')->name('answer-comments.store');

        Route::post('/comments/{comment}/up-votes','CommentUpVotesController@store')->name('comment-up-votes.store');
        Route::delete('/comments/{comment}/up-votes','CommentUpVotesController@destroy')->name('comment-up-votes.destroy');

        Route::post('/comments/{comment}/down-votes','CommentDownVotesController@store')->name('comment-down-votes.store');
        Route::delete('/comments/{comment}/down-votes','CommentDownVotesController@destroy')->name('comment-down-votes.destroy');

        Route::post('/users/{user}/avatar','UserAvatarsController@store')->name('user-avatar.store');

        Route::get('/profiles/{user}','ProfilesController@show')->name('users.show');
        Route::get('/notifications','UserNotificationsController@index')->name('user-notifications.index');
    });
});
