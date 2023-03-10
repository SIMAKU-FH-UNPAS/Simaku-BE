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
        Schema::create('pajak', function (Blueprint $table) {
            $table->id();
            $table->integer('pensiun')->nullable();
            $table->integer('bruto_pajak')->nullable();
            $table->integer('bruto_murni')->nullable();
            $table->integer('biaya_jabatan')->nullable();
            $table->integer('as_bumi_putera')->nullable();
            $table->integer('dplk_pensiun')->nullable();
            $table->integer('jml_pot_kn_pajak')->nullable();
            $table->integer('set_potongan_kn_pajak')->nullable();
            $table->integer('ptkp')->nullable();
            $table->integer('pkp')->nullable();
            $table->integer('pajak_pph21')->nullable();
            $table->integer('jml_set_pajak')->nullable();
            $table->integer('pot_tj_kena_pajak')->nullable();
            $table->bigInteger('karyawan_id')->nullable()->unsigned();
            $table->bigInteger('dosluar_id')->nullable()->unsigned();
            $table->bigInteger('dostap_id')->nullable()->unsigned();
            $table->bigInteger('pajak_tb_id')->nullable()->unsigned();
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
        Schema::dropIfExists('pajak');
    }
};
