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
        Schema::create('karyawan_pajak', function (Blueprint $table) {
            $table->id();
            $table->integer('pensiun')->nullable(); //.
            $table->integer('bruto_pajak')->nullable();
            $table->integer('bruto_murni')->nullable();
            $table->integer('biaya_jabatan')->nullable();
            $table->integer('aksa_mandiri')->nullable(); //.
            $table->integer('dplk_pensiun')->nullable(); //.
            $table->integer('jumlah_potongan_kena_pajak')->nullable();
            $table->integer('jumlah_set_potongan_kena_pajak')->nullable();
            $table->integer('ptkp')->nullable(); //.
            $table->integer('pkp')->nullable();
            $table->integer('pajak_pph21')->nullable();
            $table->integer('jumlah_set_pajak')->nullable();
            $table->integer('potongan_tak_kena_pajak')->nullable();
            $table->integer('pendapatan_bersih')->nullable();
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
        Schema::dropIfExists('karyawan_pajak');
    }
};
