<?php

use Faker\Generator as Faker;

$factory->define(App\Role::class, function (Faker $faker) {
    $name = $faker->unique()->realText(20);
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'description' => $faker->text(900),
    ];
});
