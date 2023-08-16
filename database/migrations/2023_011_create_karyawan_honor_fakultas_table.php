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
        Schema::create('karyawan_honor_fakultas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_honor_FH');
            $table->integer('besar_honor_FH');
            $table->unsignedBigInteger('karyawan_gaji_fakultas_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('karyawan_honor_fakultas', function(Blueprint $table){
            $table->foreign('karyawan_gaji_fakultas_id')->references('id')->on('karyawan_gaji_fakultas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_honor_fakultas');
    }
};
