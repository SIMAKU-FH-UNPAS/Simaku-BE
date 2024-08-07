<?php

namespace App\Models\pegawai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PegawaiGajiUniv extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'gaji_pokok',
        'tunjangan_fungsional',
        'tunjangan_struktural',
        'tunjangan_khusus_istimewa',
        'tunjangan_presensi_kerja',
        'tunjangan_tambahan',
        'tunjangan_suami_istri',
        'tunjangan_anak',
        'uang_lembur_hk',
        'uang_lembur_hl',
        'transport_kehadiran',
        'honor_universitas',
    ];

    public function master_transaksi()
    {
        return $this->hasOne(PegawaiMasterTransaksi::class, 'pegawais_gaji_univ_id', 'id');
    }
}
