<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\DeliveryAddress;


$factory->define(DeliveryAddress::class, function (Faker $faker) {
    return [
        "description" => $faker->sentence,
        "address" => $faker->address,
        'latitude' => $faker->randomFloat(6, 55, 37),
        'longitude' => $faker->randomFloat(6, 12, 7),
        "is_default" => $faker->boolean,
        "customer_id" => $faker->numberBetween(1,6),
    ];
});
