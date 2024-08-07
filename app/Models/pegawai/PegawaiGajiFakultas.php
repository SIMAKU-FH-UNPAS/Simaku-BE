<?php

namespace App\Models\pegawai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PegawaiGajiFakultas extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    public $table = "pegawai_gaji_fakulties";
    protected $fillable = [
        'gaji_fakultas'
    ];

    public function master_transaksi()
    {
        return $this->hasOne(PegawaiMasterTransaksi::class, 'pegawais_gaji_fakultas_id', 'id');
    }
}
