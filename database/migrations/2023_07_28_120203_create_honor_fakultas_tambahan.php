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
        Schema::create('honor_fakultas_tambahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gaji_fakultas_id');
            $table->string('nama_honor_FH');
            $table->integer('besar_honor_FH')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('gaji_fakultas_id')->references('id')->on('gaji_fakultas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('honor_fakultas_tambahan');
    }
};
