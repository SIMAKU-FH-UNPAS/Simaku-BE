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
        Schema::create('karyawan_gaji_universitas', function (Blueprint $table) {
            $table->id();
            $table->integer('gaji_pokok');
            $table->integer('tj_struktural');
            $table->integer('tj_pres_kerja');
            $table->integer('u_lembur_hk');
            $table->integer('u_lembur_hl');
            $table->integer('trans_kehadiran');
            $table->integer('tj_fungsional');
            $table->integer('tj_khs_istimewa');
            $table->integer('tj_tambahan');
            $table->integer('honor_univ');
            $table->integer('tj_suami_istri');
            $table->integer('tj_anak');
            $table->integer('total_gaji_univ')->nullable();
            $table->unsignedBigInteger('karyawan_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('karyawan_gaji_universitas', function(Blueprint $table){
            $table->foreign('karyawan_id')->references('id')->on('karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_gaji_universitas');
    }
};
