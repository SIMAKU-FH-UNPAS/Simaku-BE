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
        Schema::create('doslb_bank', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->unsignedBigInteger('dosen_luar_biasa_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('doslb_bank', function(Blueprint $table){
            $table->foreign('dosen_luar_biasa_id')->references('id')->on('dosen_luar_biasa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_bank');
    }
};
