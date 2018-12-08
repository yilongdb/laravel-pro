<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Layer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'status' => false ,
        'type' => $faker->randomElement(['box' , 'image' , 'icon' , 'slot' , 'text']),
        'parent_id' => null
    ];

});
