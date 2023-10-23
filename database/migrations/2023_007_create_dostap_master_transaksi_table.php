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
        Schema::create('dostap_master_transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_tetap_id');
            $table->unsignedBigInteger('dostap_bank_id');
            $table->unsignedBigInteger('dostap_gaji_universitas_id');
            $table->unsignedBigInteger('dostap_gaji_fakultas_id');
            $table->unsignedBigInteger('dostap_potongan_id');
            $table->unsignedBigInteger('dostap_pajak_id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('dostap_master_transaksi', function(Blueprint $table){
            $table->foreign('dosen_tetap_id')->references('id')->on('dosen_tetap');
            $table->foreign('dostap_bank_id')->references('id')->on('dostap_bank');
            $table->foreign('dostap_gaji_universitas_id')->references('id')->on('dostap_gaji_universitas');
            $table->foreign('dostap_gaji_fakultas_id')->references('id')->on('dostap_gaji_fakultas');
            $table->foreign('dostap_potongan_id')->references('id')->on('dostap_potongan');
            $table->foreign('dostap_pajak_id')->references('id')->on('dostap_pajak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dostap_master_transaksi');
    }
};
