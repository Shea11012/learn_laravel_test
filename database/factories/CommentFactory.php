<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Comment::class, function (Faker $faker) {
    $question = factory(\App\Models\Question::class)->create();
    return [
        'user_id' => function() {
            return factory(\App\Models\User::class)->create()->id;
        },
        'content' => $faker->text,
        'commented_id' => $question->id,
        'commented_type' => get_class($question),
    ];
});
