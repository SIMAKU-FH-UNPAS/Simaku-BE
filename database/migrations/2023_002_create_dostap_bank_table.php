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
        Schema::create('dostap_bank', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank')->nullable();
            $table->string('no_rekening')->nullable();
            $table->unsignedBigInteger('dosen_tetap_id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('dostap_bank', function(Blueprint $table){
            $table->foreign('dosen_tetap_id')->references('id')->on('dosen_tetap');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dostap_bank');
    }
};
