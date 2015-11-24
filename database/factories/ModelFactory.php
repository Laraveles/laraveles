<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Laraveles\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'name'     => $faker->name,
        'email'    => $faker->email,
        'password' => bcrypt(str_random(10))
    ];
});

$factory->define(Laraveles\Job::class, function (Faker\Generator $faker) {
    return [
        'title'       => $faker->sentence(rand(1, 5)),
        'description' => $faker->paragraph(rand(3, 8)),
        'apply'       => $faker->paragraph(rand(2, 5)),
        'city'        => $faker->city,
        'country'     => $faker->country
    ];
});