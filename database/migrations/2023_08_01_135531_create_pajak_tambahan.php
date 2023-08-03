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
        Schema::create('pajak_tambahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pajak_id');
            $table->string('nama_pajak');
            $table->integer('besar_pajak');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('pajak_id')->references('id')->on('pajak')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pajak_tambahan');
    }
};
