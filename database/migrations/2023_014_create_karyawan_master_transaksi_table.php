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
        Schema::create('karyawan_master_transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedBigInteger('karyawan_bank_id');
            $table->enum('status_bank', ['Payroll', 'Non Payroll']);
            $table->date('gaji_date_start'); //YYYY-MM-DD
            $table->date('gaji_date_end'); //YYYY-MM-DD
            $table->unsignedBigInteger('karyawan_gaji_universitas_id');
            $table->unsignedBigInteger('karyawan_gaji_fakultas_id');
            $table->unsignedBigInteger('karyawan_potongan_id');
            $table->unsignedBigInteger('karyawan_pajak_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('karyawan_master_transaksi', function(Blueprint $table){
            $table->foreign('karyawan_id')->references('id')->on('karyawan');
            $table->foreign('karyawan_bank_id')->references('id')->on('karyawan_bank');
            $table->foreign('karyawan_gaji_universitas_id')->references('id')->on('karyawan_gaji_universitas');
            $table->foreign('karyawan_gaji_fakultas_id')->references('id')->on('karyawan_gaji_fakultas');
            $table->foreign('karyawan_potongan_id')->references('id')->on('karyawan_potongan');
            $table->foreign('karyawan_pajak_id')->references('id')->on('karyawan_pajak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_master_transaksi');
    }
};
