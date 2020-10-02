<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Answer::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(\App\Models\User::class)->create()->id;
        },
        'question_id' => function() {
            return factory(\App\Models\Question::class)->create()->id;
        },
        'content' => $faker->text,
    ];
});
