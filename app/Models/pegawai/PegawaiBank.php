<?php

namespace App\Models\pegawai;

use App\Models\master\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PegawaiBank extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'nama_bank',
        'no_rekening',
        'pegawais_id'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawais_id', 'id');
    }
    public function master_transaksi()
    {
        return $this->hasMany(PegawaiMasterTransaksi::class, 'pegawais_bank_id', 'id');
    }
}
