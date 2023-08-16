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
            $table->integer('jml_pot_kn_pajak')->nullable();
            $table->integer('jml_set_pot_kn_pajak')->nullable();
            $table->integer('ptkp')->nullable(); //.
            $table->integer('pkp')->nullable();
            $table->integer('pajak_pph21')->nullable();
            $table->integer('jml_set_pajak')->nullable();
            $table->integer('pot_tk_kena_pajak')->nullable();
            $table->integer('pendapatan_bersih')->nullable();
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedBigInteger('karyawan_gaji_universitas_id');
            $table->unsignedBigInteger('karyawan_gaji_fakultas_id');
            $table->unsignedBigInteger('karyawan_potongan_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('karyawan_pajak', function(Blueprint $table){
            $table->foreign('karyawan_id')->references('id')->on('karyawan');
            $table->foreign('karyawan_gaji_universitas_id')->references('id')->on('karyawan_gaji_universitas');
            $table->foreign('karyawan_gaji_fakultas_id')->references('id')->on('karyawan_gaji_fakultas');
            $table->foreign('karyawan_potongan_id')->references('id')->on('karyawan_potongan');
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
