<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Gaji_Fakultas extends Model
{
    use HasFactory, SoftDeletes;

       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tj_tambahan',
        'honor_kinerja',
        'honor_klb_mengajar',
        'honor_mengajar_DPK',
        'peny_honor_mengajar',
        'tj_guru_besar',
        'total_gaji_FH',
        'honor_fakultas_id',
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

    public function honorfakultas(){
        return $this->belongsToMany(Honor_Fakultas::class);
    }
}
