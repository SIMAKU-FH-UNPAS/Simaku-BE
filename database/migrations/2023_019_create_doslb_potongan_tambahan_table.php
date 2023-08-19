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
        Schema::create('doslb_potongan_tambahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_potongan');
            $table->integer('besar_potongan');
            $table->unsignedBigInteger('doslb_potongan_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('doslb_potongan_tambahan', function(Blueprint $table){
            $table->foreign('doslb_potongan_id')->references('id')->on('doslb_potongan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doslb_potongan_tambahan');
    }
};
