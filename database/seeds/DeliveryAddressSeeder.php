<?php

use Illuminate\Database\Seeder;
use App\DeliveryAddress;
use Faker\Factory as Faker;

class DeliveryAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DeliveryAddress::class, 30)->create();
    }
}
