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
        Schema::create('dosen_tetap', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dostap');
            $table->string('no_pegawai_dostap');
            $table->string('golongan_dostap');
            $table->string('status_dostap');
            $table->string('jabatan_dostap');
            $table->string('alamat_KTP_dostap');
            $table->string('alamat_saatini_dostap');
            $table->string('nama_bank_dostap');
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
        Schema::dropIfExists('dosen_tetap');
    }
};
