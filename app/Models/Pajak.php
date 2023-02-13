<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pajak extends Model
{
    use HasFactory, SoftDeletes;


        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pensiun',
        'bruto_pajak',
        'bruto_murni',
        'biaya_jabatan',
        'as_bumi_putera',
        'dplk_pensiun',
        'jml_pot_kn_pajak',
        'set_potongan_kn_pajak',
        'ptkp',
        'pkp',
        'pajak_pph21',
        'jml_set_pajak',
        'pot_tj_kena_pajak',
        'pajak_tb_id',
        'karyawan_id',
        'dosluar_id',
        'dostap_id'
    ];

    public function dosenluarbiasa()
    {
        return $this->belongsTo(Dosen_LuarBiasa::class);
    }


    public function dosentetap()
    {
        return $this->belongsTo(Dosen_Tetap::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function pajaktambahan(){
        return $this->belongsTo(Pajak_Tambahan::class);
    }
}
