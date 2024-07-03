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
        Schema::create('doslb_master_transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dosen_luar_biasa_id');
            $table->unsignedBigInteger('doslb_bank_id');
            $table->enum('status_bank', ['Payroll', 'Non Payroll']);
            $table->date('gaji_date_start'); //YYYY-MM-DD
            $table->date('gaji_date_end'); //YYYY-MM-DD
            $table->unsignedBigInteger('doslb_komponen_pendapatan_id');
            $table->unsignedBigInteger('doslb_potongan_id');
            $table->unsignedBigInteger('doslb_pajak_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('doslb_master_transaksi', function(Blueprint $table){
            $table->foreign('dosen_luar_biasa_id')->references('id')->on('dosen_luar_biasa');
            $table->foreign('doslb_bank_id')->references('id')->on('doslb_bank');
            $table->foreign('doslb_komponen_pendapatan_id')->references('id')->on('doslb_komponen_pendapatan');
            $table->foreign('doslb_potongan_id')->references('id')->on('doslb_potongan');
            $table->foreign('doslb_pajak_id')->references('id')->on('doslb_pajak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doslb_master_transaksi');
    }
};
