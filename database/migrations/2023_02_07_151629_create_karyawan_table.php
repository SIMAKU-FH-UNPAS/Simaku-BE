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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_karyawan');
            $table->string('no_pegawai_karyawan');
            $table->string('golongan_karyawan');
            $table->string('status_karyawan');
            $table->string('jabatan_karyawan');
            $table->string('alamat_KTP_karyawan');
            $table->string('alamat_saatini_karyawan');
            $table->string('nama_bank_karyawan');
            $table->bigInteger('total_pendapatan_id')->unsigned();
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
        Schema::dropIfExists('karyawan');
    }
};
