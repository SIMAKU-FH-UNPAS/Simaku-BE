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
        Schema::table('kinerjas', function (Blueprint $table) {
            //
            $table->enum('jenis', ['fungsional', 'kinerja', 'tambahan'])->nullable()->after('nama');
            $table->text('deskripsi')->nullable()->after('jenis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kinerjas', function (Blueprint $table) {
            //
            $table->dropColumn('tgl_awal');
            $table->dropColumn('tgl_akhir');
        });
    }
};
