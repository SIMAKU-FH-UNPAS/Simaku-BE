<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dostap_potongan_tambahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_potongan');
            $table->integer('besar_potongan');
            $table->unsignedBigInteger('dostap_potongan_id');
            $table->softDeletes();
            $table->timestamps();
        });
            Schema::table('dostap_potongan_tambahan', function(Blueprint $table){
            $table->foreign('dostap_potongan_id')->references('id')->on('dostap_potongan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potongan_tambahan');
    }
};
