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
        Schema::create('doslb_potongan', function (Blueprint $table) {
            $table->id();
            $table->integer('sp_FH')->nullable();
            $table->integer('infaq')->nullable();
            $table->integer('total_potongan')->nullable();
            $table->unsignedBigInteger('dosen_luar_biasa_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('doslb_potongan', function(Blueprint $table){
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
        Schema::dropIfExists('doslb_potongan');
    }
};
