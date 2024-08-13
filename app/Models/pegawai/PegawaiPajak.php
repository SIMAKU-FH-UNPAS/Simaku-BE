<?php

namespace App\Models\pegawai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PegawaiPajak extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;
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
        'pajak_pph25',
        'jumlah_set_pajak',
        'potongan_tak_kena_pajak',
        'pendapatan_bersih',
    ];

    public function master_transaksi()
    {
        return $this->hasOne(PegawaiMasterTransaksi::class, 'pegawais_pajak_id', 'id');
    }
}
