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
        Schema::create('gaji_fakultas', function (Blueprint $table) {
            $table->id();
            $table->integer('tj_tambahan')->nullable();
            $table->integer('honor_kinerja')->nullable();
            $table->integer('honor_klb_mengajar')->nullable();
            $table->integer('honor_mengajar_DPK')->nullable();
            $table->integer('peny_honor_mengajar')->nullable();
            $table->integer('tj_guru_besar')->nullable();
            $table->bigInteger('honor_fakultas_id')->nullable()->unsigned();
            $table->bigInteger('pegawai_id')->unsigned();
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
        Schema::dropIfExists('gaji_fakultas');
    }
};
