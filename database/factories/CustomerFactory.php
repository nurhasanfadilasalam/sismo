<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Customer;
use Illuminate\Support\Str;




$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'mobile' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'address' => $faker->address,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'api_token' => Str::random(10),
        'device_token' => Str::random(10),
        'remember_token' => Str::random(10),
    ];
});
