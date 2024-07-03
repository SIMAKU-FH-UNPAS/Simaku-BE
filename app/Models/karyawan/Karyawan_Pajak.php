<?php

namespace App\Models\karyawan;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    'jumlah_potongan_kena_pajak',
    'jumlah_set_potongan_kena_pajak',
    'ptkp',
    'pkp',
    'pajak_pph21',
    'jumlah_set_pajak',
    'potongan_tak_kena_pajak',
    'pendapatan_bersih'
];

public function master_transaksi(){
    return $this->hasOne(Karyawan_Master_Transaksi::class,'karyawan_pajak_id', 'id');
}
}

