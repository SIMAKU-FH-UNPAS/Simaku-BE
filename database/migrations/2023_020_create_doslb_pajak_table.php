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
        Schema::create('doslb_pajak', function (Blueprint $table) {
            $table->id();
            $table->integer('pajak_pph25')->nullable();
            $table->integer('pendapatan_bersih')->nullable();
            $table->unsignedBigInteger('dosen_luar_biasa_id');
            $table->unsignedBigInteger('doslb_pendapatan_id');
            $table->unsignedBigInteger('doslb_potongan_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('doslb_pajak', function(Blueprint $table){
            $table->foreign('dosen_luar_biasa_id')->references('id')->on('dosen_luar_biasa');
            $table->foreign('doslb_pendapatan_id')->references('id')->on('doslb_komponen_pendapatan');
            $table->foreign('doslb_potongan_id')->references('id')->on('doslb_potongan');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doslb_pajak');
    }
};
