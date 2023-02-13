<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Gaji_Universitas extends Model
{
    use HasFactory, SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gaji_pokok',
        'tj_struktural',
        'tj_pres_kerja',
        'u_lembur_hk',
        'u_lembur_hl',
        'trans_kehadiran',
        'tj_fungsional',
        'gaji_pusat',
        'tj_khs_istimewa',
        'tj_tambahan',
        'honor_univ',
        'tj_suami_istri',
        'tj_anak',
        'karyawan_id',
        'dosluar_id',
        'dostap_id'
    ];

    public function dosenluarbiasa()
    {
        return $this->belongsTo(Dosen_LuarBiasa::class);
    }


    public function dosentetap()
    {
        return $this->belongsTo(Dosen_Tetap::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
