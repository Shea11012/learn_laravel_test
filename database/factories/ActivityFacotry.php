<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Activity::class, function (Faker $faker) {
    $question = factory(\App\Models\Question::class)->create();
    return [
        'user_id' => function() {
            return factory(\App\Models\User::class)->create()->id;
        },
        'subject_id' => $question->id,
        'subject_type' => get_class($question),
        'type' => 'published_question',
    ];
});
