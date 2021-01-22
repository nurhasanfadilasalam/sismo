<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('purchases_id')->unsigned();
            $table->bigInteger('inventory_id')->unsigned(); 
            $table->bigInteger('stocks_id')->unsigned();
            $table->string('unit');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases_detail');
    }
}
