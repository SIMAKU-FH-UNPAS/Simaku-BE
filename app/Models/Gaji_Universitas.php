<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gaji_Universitas extends Model
{
    use HasFactory, SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "gaji_universitas";
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
        'total_gaji_univ',
        'pegawai_id'
    ];

    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }

}
