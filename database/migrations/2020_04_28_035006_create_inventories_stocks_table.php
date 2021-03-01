<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('inventories_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('inventories_id')->unsigned();
            $table->date('expired');
            $table->integer('price');
            $table->integer('stock');
            $table->text('notes')->nullable();
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
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
        Schema::dropIfExists('inventories_stocks');
    }
}
