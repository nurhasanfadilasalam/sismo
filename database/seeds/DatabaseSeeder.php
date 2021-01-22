<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(CustomerSeeder::class);
        $this->call(DemoSeeder::class);
        // $this->call(MastersSeeder::class);
        // $this->call(DeliveryAddressSeeder::class);
        $this->call(OrderStatusesSeeder::class);
        // $this->call(PaymentsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
        // $this->call(CategoriesTableSeeder::class);
        
        
    }
}
