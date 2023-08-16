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
        Schema::create('karyawan_gaji_fakultas', function (Blueprint $table) {
            $table->id();
            $table->integer('tj_tambahan')->nullable();
            $table->integer('honor_kinerja')->nullable();
            $table->integer('honor')->nullable();
            $table->integer('total_gaji_fakultas')->nullable();
            $table->unsignedBigInteger('karyawan_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('karyawan_gaji_fakultas', function(Blueprint $table){
            $table->foreign('karyawan_id')->references('id')->on('karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_gaji_fakultas');
    }
};
