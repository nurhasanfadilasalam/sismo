<?php

use App\Masters;
use Illuminate\Database\Seeder;

class MastersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Masters::insert([
            ['created_at' => date('Y-m-d H:i:s'), 'created_by' => '1', 'value' => 'Makanan', 'extra' => '', 'type' => 'inventoryType'],
            ['created_at' => date('Y-m-d H:i:s'), 'created_by' => '1', 'value' => 'Minuman', 'extra' => '', 'type' => 'inventoryType'],
            ['created_at' => date('Y-m-d H:i:s'), 'created_by' => '1', 'value' => 'Snack', 'extra' => '', 'type' => 'inventoryType'],
            ['created_at' => date('Y-m-d H:i:s'), 'created_by' => '1', 'value' => 'Jamu', 'extra' => '', 'type' => 'inventoryType'],
            ['created_at' => date('Y-m-d H:i:s'), 'created_by' => '1', 'value' => 'Umum', 'extra' => '', 'type' => 'priceType'],
            ['created_at' => date('Y-m-d H:i:s'), 'created_by' => '1', 'value' => 'Langganan', 'extra' => '', 'type' => 'priceType'],
            ['created_at' => date('Y-m-d H:i:s'), 'created_by' => '1', 'value' => 'Partner', 'extra' => '', 'type' => 'priceType'],
        ]);
    }
}
