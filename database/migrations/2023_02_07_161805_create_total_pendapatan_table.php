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
            $table->integer('total_gajiuniv');
            $table->integer('total_gajifak');
            $table->integer('total_potongan');
            $table->integer('total_pajak');
            $table->bigInteger('pegawai_id')->unsigned();
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
