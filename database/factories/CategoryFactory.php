<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Categorie::class, function (Faker $faker) {
    return [
        'name'=>$faker->randomElement(['Makanan','Minuman','Bakso','Jamu','Snack']),
        'description'=>$faker->sentences(5,true),
    ];
});
