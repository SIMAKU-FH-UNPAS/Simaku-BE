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
        Schema::create('doslb_komponen_pendapatan_tambahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen');
            $table->integer('besar_komponen');
            $table->unsignedBigInteger('doslb_pendapatan_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('doslb_komponen_pendapatan_tambahan', function(Blueprint $table){
            $table->foreign('doslb_pendapatan_id')->references('id')->on('doslb_komponen_pendapatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doslb_komponen_pendapatan_tambahan');
    }
};
