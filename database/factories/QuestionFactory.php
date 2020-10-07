<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(App\Models\Question::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(\App\Models\User::class)->create()->id;
        },
        'category_id' => function() {
            return factory(Category::class)->create();
        },
        'title' => $faker->sentence,
        'content' => $faker->text,
    ];
});

$factory->state(\App\Models\Question::class,'published',function ($faker) {
    return [
        'published_at' => \Carbon\Carbon::parse('-1 week'),
    ];
});

$factory->state(\App\Models\Question::class,'unpublished',function ($faker) {
    return [
        'published_at' => null,
    ];
});
