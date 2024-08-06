<?php

namespace App\Models\master;

use App\Models\pegawai\KinerjaTambahan;
use App\Models\pegawai\PegawaiBank;
use App\Models\pegawai\PegawaiMasterTransaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Pegawai extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'nama',
        'no_pegawai',
        'npwp',
        'status',
        'golongan',
        'tipe_pegawai',
        'jabatan',
        'alamat_ktp',
        'alamat_saat_ini',
        'nomor_hp'
    ];

    public function banks()
    {
        return $this->hasMany(PegawaiBank::class, 'pegawais_id', 'id');
    }

    public function kinerja_tambahan()
    {
        return $this->hasMany(KinerjaTambahan::class, 'pegawais_id', 'id');
    }

    // public function master_transaksi()
    // {
    //     return $this->hasMany(PegawaiMasterTransaksi::class, 'pegawais_id', 'id');
    // }
}
