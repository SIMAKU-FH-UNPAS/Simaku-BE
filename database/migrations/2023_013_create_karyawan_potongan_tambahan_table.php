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
        Schema::create('karyawan_potongan_tambahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_potongan');
            $table->integer('besar_potongan');
            $table->unsignedBigInteger('karyawan_potongan_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('karyawan_potongan_tambahan', function(Blueprint $table){
            $table->foreign('karyawan_potongan_id')->references('id')->on('karyawan_potongan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_potongan_tambahan');
    }
};
