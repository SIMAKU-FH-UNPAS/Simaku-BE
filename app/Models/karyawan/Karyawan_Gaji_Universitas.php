<?php

namespace App\Models\karyawan;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Karyawan_Gaji_Universitas extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "karyawan_gaji_universitas";
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
        'honor_universitas'
    ];

    public function master_transaksi()
    {
        return $this->hasOne(Karyawan_Master_Transaksi::class, 'karyawan_gaji_universitas_id', 'id');
    }
}
