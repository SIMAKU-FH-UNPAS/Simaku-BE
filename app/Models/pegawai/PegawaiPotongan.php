<?php

namespace App\Models\pegawai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PegawaiPotongan extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'potongan'
    ];

    public function master_transaksi()
    {
        return $this->hasOne(PegawaiMasterTransaksi::class, 'pegawais_potongan_id', 'id');
    }
}
