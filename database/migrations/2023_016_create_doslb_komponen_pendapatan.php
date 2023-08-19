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
        Schema::create('doslb_komponen_pendapatan', function (Blueprint $table) {
            $table->id();
            $table->integer('honor_mengajar')->nullable();
            $table->integer('tj_guru_besar')->nullable();
            $table->integer('honor_kinerja')->nullable();
            $table->integer('tj_tambahan')->nullable();
            $table->integer('total_komponen_pendapatan')->nullable();
            $table->unsignedBigInteger('dosen_luar_biasa_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('doslb_komponen_pendapatan', function(Blueprint $table){
            $table->foreign('dosen_luar_biasa_id')->references('id')->on('dosen_luar_biasa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doslb_komponen_pendapatan');
    }
};
