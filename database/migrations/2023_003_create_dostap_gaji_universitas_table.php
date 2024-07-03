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
        Schema::create('dostap_gaji_universitas', function (Blueprint $table) {
            $table->id();
            $table->integer('gaji_pokok');
            $table->integer('tunjangan_fungsional');
            $table->integer('tunjangan_struktural');
            $table->integer('tunjangan_khusus_istimewa');
            $table->integer('tunjangan_presensi_kerja');
            $table->integer('tunjangan_tambahan');
            $table->integer('tunjangan_suami_istri');
            $table->integer('tunjangan_anak');
            $table->integer('uang_lembur_hk');
            $table->integer('uang_lembur_hl');
            $table->integer('transport_kehadiran');
            $table->integer('honor_universitas');
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
        Schema::dropIfExists('dostap_gaji_universitas');
    }
};
