<?php

namespace App\Models;

use App\Models\Pegawai;
use App\Models\Pajak_Tambahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pajak extends Model
{
    use HasFactory, SoftDeletes;


        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "pajak";
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
        'pot_tk_kena_pajak',
        'total_pajak',
        'pegawai_id'
    ];

    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }

    public function pajaktambahan(){
        return $this->hasMany(Pajak_Tambahan::class,'pajak_id', 'id');
    }

    public function total_pajak($request){
        $pensiun = $request-> pensiun;
        $bruto_pajak = $request-> bruto_pajak;
        $bruto_murni = $request-> bruto_murni;
        $biaya_jabatan = $request-> biaya_jabatan;
        $as_bumi_putera = $request-> as_bumi_putera;
        $dplk_pensiun = $request-> dplk_pensiun;
        $jml_pot_kn_pajak = $request-> jml_pot_kn_pajak;
        $set_potongan_kn_pajak = $request-> set_potongan_kn_pajak;
        $ptkp = $request-> ptkp;
        $pkp = $request-> pkp;
        $pajak_pph21 = $request-> pajak_pph21;
        $jml_set_pajak = $request-> jml_set_pajak;
        $pot_tk_kena_pajak = $request-> pot_tk_kena_pajak;
        $total_pajak = $pensiun+$bruto_pajak+$bruto_murni+$biaya_jabatan+$as_bumi_putera+$dplk_pensiun+$jml_pot_kn_pajak+$set_potongan_kn_pajak+$ptkp+$pkp+$pajak_pph21+$jml_set_pajak+$pot_tk_kena_pajak;

        return $total_pajak;
    }
}
