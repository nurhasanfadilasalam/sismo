<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */




    public function run()
    {
        $test = new \App\Product;
        $test->name = "Bakso Iga";
        $test->price = "15000";
        $test->discount_price = "2000";
        $test->description = "Ini adalah makanan lezat nikmat halal";
        $test->weight = "1.0";
        $test->package_items_count = "1";
        $test->unit = "Bungkus";
        $test->featured = "0";
        $test->deliverable= "1";
        $test->image= "";
        $test->categorie_id= "1";
        $test->save();
        
        $test = new \App\Product;
        $test->name = "Mie Ayam Bakso";
        $test->price = "15000";
        $test->discount_price = "2000";
        $test->description = "Ini adalah makanan lezat nikmat halal";
        $test->weight = "1.0";
        $test->package_items_count = "1";
        $test->unit = "Bungkus";
        $test->featured = "0";
        $test->deliverable= "1";
        $test->image= "";
        $test->categorie_id= "1";
        $test->save();


        $test = new \App\Product;
        $test->name = "Mie Ayam";
        $test->price = "15000";
        $test->discount_price = "2000";
        $test->description = "Ini adalah makanan lezat nikmat halal";
        $test->weight = "1.0";
        $test->package_items_count = "1";
        $test->unit = "Bungkus";
        $test->featured = "0";
        $test->deliverable= "1";
        $test->image= "";
        $test->categorie_id= "1";
        $test->save();


        $test = new \App\Product;
        $test->name = "Bakso Besar";
        $test->price = "15000";
        $test->discount_price = "2000";
        $test->description = "Ini adalah makanan lezat nikmat halal";
        $test->weight = "1.0";
        $test->package_items_count = "1";
        $test->unit = "Bungkus";
        $test->featured = "0";
        $test->deliverable= "1";
        $test->image= "";
        $test->categorie_id= "1";
        $test->save();
  
    }
}
