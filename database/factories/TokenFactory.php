<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Token::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'value' => $faker->sentence(10),
        'status' => false ,
        'type' => $faker->randomElement(['radius' , 'color' , 'border' , 'font']),
    ];
});
