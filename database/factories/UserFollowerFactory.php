<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\UserFollower;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(UserFollower::class, function (Faker $faker) {
    return [
        'user_id' => '',
        'follower_id' => '',
    ];
});
