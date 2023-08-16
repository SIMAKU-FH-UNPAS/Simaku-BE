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
        Schema::create('karyawan_potongan', function (Blueprint $table) {
            $table->id();
            $table->integer('sp_FH')->nullable();
            $table->integer('infaq')->nullable();
            $table->integer('total_potongan')->nullable();
            $table->unsignedBigInteger('karyawan_id');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('karyawan_potongan', function(Blueprint $table){
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
        Schema::dropIfExists('karyawan_potongan');
    }
};
