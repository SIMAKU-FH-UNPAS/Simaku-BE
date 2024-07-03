<?php

namespace App\Models\dosentetap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosentetap\Dostap_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Gaji_Universitas extends Model
{
    use HasFactory, SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "dostap_gaji_universitas";
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

    public function master_transaksi(){
        return $this->hasOne(Dostap_Master_Transaksi::class,'dostap_gaji_universitas_id', 'id');
    }


}
