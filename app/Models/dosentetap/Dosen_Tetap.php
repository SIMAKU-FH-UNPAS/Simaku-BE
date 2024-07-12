<?php

namespace App\Models\dosentetap;

use App\Models\dosentetap\Dostap_Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosentetap\Dostap_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Dosen_Tetap extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "dosen_tetap";
    protected $fillable = [
        'nama',
        'no_pegawai',
        'npwp',
        'status',
        'golongan',
        'jabatan',
        'alamat_KTP',
        'alamat_saat_ini',
        'nomor_hp'
    ];


    public function banks()
    {
        return $this->hasMany(Dostap_Bank::class, 'dosen_tetap_id', 'id');
    }
    public function master_transaksi()
    {
        return $this->hasMany(Dostap_Master_Transaksi::class, 'dosen_tetap_id', 'id');
    }
}
