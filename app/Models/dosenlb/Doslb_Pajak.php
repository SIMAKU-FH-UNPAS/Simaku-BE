<?php

namespace App\Models\dosenlb;

use App\Models\dosenlb\Doslb_Potongan;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doslb_Pajak extends Model
{
    use HasFactory, SoftDeletes;


    /**
 * The attributes that are mass assignable.
 *
 * @var array<int, string>
 */
public $table = "doslb_pajak";

protected $fillable = [
    'dosen_luar_biasa_id',
    'doslb_pendapatan_id',
    'doslb_potongan_id',
    'pajak_pph25',
    'pendapatan_bersih',
];

public function dosen_luar_biasa(){
    return $this->belongsTo(Dosen_Luar_Biasa::class, 'dosen_luar_biasa_id', 'id');
}
public function komponenpendapatan(){
    return $this->belongsTo(Doslb_Komponen_Pendapatan::class, 'doslb_pendapatan_id', 'id');
}
public function potongan(){
    return $this->belongsTo(Doslb_Potongan::class,'doslb_potongan_id', 'id');
}

// Menyimpan nilai properti
protected $pajak_pph25;
protected $pendapatan_bersih;


public function hitung_pajak_pph25($request){
    $komponenpendapatan = $request->doslb_pendapatan_id;

    $total_komponen_pendapatan =  Doslb_Komponen_Pendapatan::findOrFail($komponenpendapatan)->total_komponen_pendapatan;

    $pajak_pph25 = $total_komponen_pendapatan * 0.5 * 0.06;
    $this->pajak_pph25 = $pajak_pph25;
    return $pajak_pph25;
}


public function hitung_pendapatan_bersih($request){
$pajak_pph25 = $this->pajak_pph25;
$komponenpendapatan = $request->doslb_pendapatan_id;
$potongan = $request->doslb_potongan_id;

$total_komponen_pendapatan =  Doslb_Komponen_Pendapatan::findOrFail($komponenpendapatan)->total_komponen_pendapatan;
$total_potongan = Doslb_Potongan::findOrFail($potongan)->total_potongan;


$pendapatan_bersih = $total_komponen_pendapatan-($total_potongan+$pajak_pph25);
$this->pendapatan_bersih = $pendapatan_bersih;
return $pendapatan_bersih;
}
}

