<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ManydemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i=0; $i < 15; $i++) { 
            DB::table('users')->insert([
                'username' => $faker->userName,
                'name' => $faker->name,
                'email' => $faker->email,
                'roles' => json_encode(["STAFF"]),
                'password' => \Hash::make("demo12345"),
                'avatar' => "",
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
            ]);
        }
    }
}
