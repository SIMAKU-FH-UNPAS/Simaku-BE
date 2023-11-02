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
            $table->string('nama');
            $table->string('no_pegawai');
            $table->string('npwp');
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
            $table->enum('golongan', ['IIA','IIB','IIC','IID','IIIA','IIIB','IIIC','IIID','IVA','IVB','IVC','IVD','IVE']);
            $table->string('jabatan');
            $table->string('alamat_KTP');
            $table->string('alamat_saatini');
            $table->string('nama_bank_utama');
            $table->string('nama_bank_tambahan');
            $table->string('norek_bank_utama');
            $table->string('norek_bank_tambahan');
            $table->string('nomor_hp');
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
        Schema::dropIfExists('dosen_tetap');
    }
};
