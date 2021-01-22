<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories_tracks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('inventories_id')->unsigned();
            $table->integer('stocks_id')->unsigned();
            $table->integer('qty');
            $table->string('unit');
            $table->integer('price');
            $table->date('expired');
            $table->string('type'); // in / out / return
            $table->text('note')->nullable();
            $table->integer("created_by");
            $table->softDeletes();
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
        Schema::dropIfExists('inventories_tracks');
    }
}
