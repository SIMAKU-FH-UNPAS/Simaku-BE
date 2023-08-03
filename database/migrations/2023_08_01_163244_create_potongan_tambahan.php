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
        Schema::create('potongan_tambahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('potongan_id');
            $table->string('nama_potongan');
            $table->integer('besar_potongan');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('potongan_id')->references('id')->on('potongan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potongan_tambahan');
    }
};
