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
        Schema::create('total_pendapatan', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_pendapatan');
            $table->integer('jumlah_potongan');
            $table->integer('pendapatan_bersih');
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
        Schema::dropIfExists('total_pendapatan');
    }
};
