<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan_Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Karyawan extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "karyawan";
    protected $fillable = [
        'nama',
        'no_pegawai',
        'npwp',
        'status',
        'golongan',
        'jabatan',
        'alamat_KTP',
        'alamat_saat_ini',
        'nama_bank_utama',
        'nama_bank_tambahan',
        'norek_bank_utama',
        'norek_bank_tambahan',
        'nomor_hp'
    ];

    public function banks()
    {
        return $this->hasMany(Karyawan_Bank::class, 'karyawan_id', 'id');
    }
    public function master_transaksi()
    {
        return $this->hasMany(Karyawan_Master_Transaksi::class, 'karyawan_id', 'id');
    }
}
