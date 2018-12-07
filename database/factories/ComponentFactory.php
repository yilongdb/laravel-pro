<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Component::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
