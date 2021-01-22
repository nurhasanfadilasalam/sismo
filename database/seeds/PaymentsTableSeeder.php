<?php

use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Payment::insert([
        //     [],
        //     [],
        //     [],
        //     [],
        //     [],
        //     ['price' => "90000", 'description' => 'Pengataran Siang', 'customer_id' => '7', 'status' => 'unpaid', 'method' => 'COD', 'created_at' => date('Y-m-d H:i:s')],
        //     ['price' => "80000", 'description' => 'Pengataran Siang', 'customer_id' => '12', 'status' => 'unpaid', 'method' => 'COD', 'created_at' => date('Y-m-d H:i:s')],
        //     ['price' => "60000", 'description' => 'Pengataran Siang', 'customer_id' => '3', 'status' => 'unpaid', 'method' => 'COD', 'created_at' => date('Y-m-d H:i:s')],
            
        // ]);


        \DB::table('payments')->delete();
        
        \DB::table('payments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'price' => "40000", 'description' => 'Pengataran Siang', 'customer_id' => '10', 'status' => 'unpaid', 'method' => 'COD', 'created_at' => date('Y-m-d H:i:s')
            ),
            1 => 
            array (
                'id' => 2,
                'price' => "50000", 'description' => 'Pengataran Siang', 'customer_id' => '1', 'status' => 'unpaid', 'method' => 'COD', 'created_at' => date('Y-m-d H:i:s')
            ),
            2 => 
            array (
                'id' => 3,
                'price' => "100000", 'description' => 'Pengataran Siang', 'customer_id' => '8', 'status' => 'unpaid', 'method' => 'COD', 'created_at' => date('Y-m-d H:i:s')
            ),
            3 => 
            array (
                'id' => 4,
                'price' => "140000", 'description' => 'Pengataran Siang', 'customer_id' => '5', 'status' => 'unpaid', 'method' => 'COD', 'created_at' => date('Y-m-d H:i:s')
            ),
            4 => 
            array (
                'id' => 5,
                'price' => "150000", 'description' => 'Pengataran Siang', 'customer_id' => '6', 'status' => 'unpaid', 'method' => 'COD', 'created_at' => date('Y-m-d H:i:s')
            ),
        ));
    }
}
