<?php

use Illuminate\Database\Seeder;
use App\Customer;


class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Customer::class, 30)->create();
    }
}
