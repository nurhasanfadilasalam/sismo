<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrafficTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('traffic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('perangkat_id')->unsigned();
            $table->integer('nilai');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('traffic');
    }
}
