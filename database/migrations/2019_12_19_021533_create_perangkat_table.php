<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerangkatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perangkat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("gedung_id");
            $table->string('nama_perangkat');
            $table->string('ip_perangkat');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('perangkat');
    }
}
