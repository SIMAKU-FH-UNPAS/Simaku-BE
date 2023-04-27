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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_pegawai');
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
            $table->enum('posisi', ['Dosen Tetap', 'Dosen Luar Biasa', 'Karyawan']);
            $table->bigInteger('golongan_id')->unsigned();
            $table->string('jabatan');
            $table->string('alamat_KTP');
            $table->string('alamat_saatini');
            $table->string('nama_bank');
            $table->string('norek_bank');
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
        Schema::dropIfExists('pegawai');
    }
};
