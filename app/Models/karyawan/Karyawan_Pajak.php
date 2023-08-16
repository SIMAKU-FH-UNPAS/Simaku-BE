<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan_Potongan;
use App\Models\karyawan\Karyawan;
use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use App\Models\karyawan\Karyawan_Gaji_Universitas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan_Pajak extends Model
{
    use HasFactory, SoftDeletes;


    /**
 * The attributes that are mass assignable.
 *
 * @var array<int, string>
 */
public $table = "karyawan_pajak";

protected $fillable = [
    'pensiun',
    'bruto_pajak',
    'bruto_murni',
    'biaya_jabatan',
    'aksa_mandiri',
    'dplk_pensiun',
    'jml_pot_kn_pajak',
    'jml_set_pot_kn_pajak',
    'ptkp',
    'pkp',
    'pajak_pph21',
    'jml_set_pajak',
    'pot_tk_kena_pajak',
    'pendapatan_bersih',
    'karyawan_id',
    'karyawan_gaji_universitas_id',
    'karyawan_gaji_fakultas_id',
    'karyawan_potongan_id'
];

public function karyawan(){
    return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
}
public function gaji_universitas(){
    return $this->belongsTo(Karyawan_Gaji_Universitas::class, 'karyawan_gaji_universitas_id', 'id');
}

public function gaji_fakultas(){
    return $this->belongsTo(Karyawan_Gaji_Fakultas::class,'karyawan_gaji_fakultas_id', 'id');
}
public function potongan(){
    return $this->belongsTo(Karyawan_Potongan::class,'karyawan_potongan_id', 'id');
}

// Menyimpan nilai properti
protected $bruto_pajak;
protected $bruto_murni;
protected $biaya_jabatan;
protected $jml_pot_kn_pajak;
protected $jml_set_pot_kn_pajak;
protected $pkp;
protected $pajak_pph21;
protected $jml_set_pajak;
protected $pot_tk_kena_pajak;
protected $pendapatan_bersih;


public function hitung_bruto_pajak($request){
    $gajiuniversitas = $request->karyawan_gaji_universitas_id;
    $gajifakultas = $request->karyawan_gaji_fakultas_id;

    $total_gaji_univ =  Karyawan_Gaji_Universitas::findOrFail($gajiuniversitas)->total_gaji_univ;
    $total_gaji_fakultas =  Karyawan_Gaji_Fakultas::findOrFail($gajifakultas)->total_gaji_fakultas;
    $pensiun = $request->pensiun;
        $bruto_pajak = $total_gaji_univ + $total_gaji_fakultas + $pensiun;
        $this->bruto_pajak = $bruto_pajak;
        return $bruto_pajak;
    }
public function hitung_bruto_murni($request){
    $gajiuniversitas = $request->karyawan_gaji_universitas_id;
    $gajifakultas = $request->karyawan_gaji_fakultas_id;

    $total_gaji_univ =  Karyawan_Gaji_Universitas::findOrFail($gajiuniversitas)->total_gaji_univ;
    $total_gaji_fakultas =  Karyawan_Gaji_Fakultas::findOrFail($gajifakultas)->total_gaji_fakultas;

    $bruto_murni = $total_gaji_univ + $total_gaji_fakultas;
    $this->bruto_murni = $bruto_murni;
    return $bruto_murni;
}
public function hitung_biaya_jabatan(){
    // Get nilai dari properti $bruto_murni
    $bruto_murni = $this->bruto_murni;

    // Menghitung Biaya Jabatan
    $biaya_jabatan = $bruto_murni * 0.05; // 5 % dari bruto murni
    $this->biaya_jabatan = $biaya_jabatan;
    return $biaya_jabatan;
}
public function hitung_jml_pot_kn_pajak($request){
$pensiun = $request->pensiun;
$aksa_mandiri = $request->aksa_mandiri;
$dplk_pensiun = $request->dplk_pensiun;

    $jml_pot_kn_pajak = $pensiun + $aksa_mandiri + $dplk_pensiun;
    $this->jml_pot_kn_pajak = $jml_pot_kn_pajak;
    return $jml_pot_kn_pajak;
}
public function hitung_jml_set_pot_kn_pajak(){
    $bruto_pajak = $this->bruto_pajak;
    $jml_pot_kn_pajak = $this->jml_pot_kn_pajak;

    $jml_set_pot_kn_pajak = $bruto_pajak - $jml_pot_kn_pajak;
    $this->jml_set_pot_kn_pajak = $jml_set_pot_kn_pajak;
    return $jml_set_pot_kn_pajak;
}
public function hitung_pkp($request){
$jml_set_pot_kn_pajak = $this->jml_set_pot_kn_pajak;
$ptkp = $request->ptkp;

$pkp = $jml_set_pot_kn_pajak - $ptkp;
$this->pkp = $pkp;
return $pkp;
}
public function hitung_pajak_pph21($request){
    $pkp = $this->pkp;
    $pajak_pph21 = $pkp * 0.05; // Pajak PPh21 = PKP x 5%

// Simpan nilai pada property
$this->pajak_pph21 = $pajak_pph21;
return $pajak_pph21;
}
public function hitung_jml_set_pajak($request){
$bruto_pajak = $this->bruto_pajak;
$pajak_pph21 = $this->pajak_pph21;
$pensiun = $request->pensiun;
$aksa_mandiri = $request->aksa_mandiri;
$dplk_pensiun = $request->dplk_pensiun;

$jml_set_pajak = $bruto_pajak-($dplk_pensiun+$aksa_mandiri+$pensiun+$pajak_pph21);
$this->jml_set_pajak = $jml_set_pajak;
return $jml_set_pajak;
}
public function hitung_pot_tk_kena_pajak($request){
$potongan = $request->karyawan_potongan_id;
$pot_tk_kena_pajak =  Karyawan_Potongan::findOrFail($potongan)->total_potongan;

$this->pot_tk_kena_pajak = $pot_tk_kena_pajak;
return $pot_tk_kena_pajak;
}
public function hitung_pendapatan_bersih(){
$jml_set_pot_kn_pajak = $this->jml_set_pot_kn_pajak;
$pot_tk_kena_pajak = $this->pot_tk_kena_pajak;

$pendapatan_bersih = $jml_set_pot_kn_pajak-$pot_tk_kena_pajak;
$this->pendapatan_bersih = $pendapatan_bersih;
return $pendapatan_bersih;
}
}

