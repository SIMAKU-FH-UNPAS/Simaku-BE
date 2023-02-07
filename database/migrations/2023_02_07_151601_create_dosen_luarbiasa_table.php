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
        Schema::create('dosen_luarbiasa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dosluar');
            $table->string('no_pegawai_dosluar');
            $table->string('golongan_dosluar');
            $table->string('status_dosluar');
            $table->string('jabatan_dosluar');
            $table->string('alamat_KTP_dosluar');
            $table->string('alamat_saatini_dosluar');
            $table->string('nama_bank_dosluar');
            $table->softDeletes();
            $table->bigInteger('total_pendapatan_id')->unsigned();
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
        Schema::dropIfExists('dosen_luarbiasa');
    }
};
