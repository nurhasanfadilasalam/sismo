<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogstatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logstatuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_perangkat');
            $table->string('ip_perangkat')->nullable();
            $table->string('status')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('gedung')->default('puskom');; // static: orderType, paymentType,
            $table->integer("created_by");
            $table->integer("updated_by")->nullable();
            $table->integer("deleted_by")->nullable();
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
        Schema::dropIfExists('logstatuses');
    }
}
