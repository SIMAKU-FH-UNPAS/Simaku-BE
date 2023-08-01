<?php

namespace App\Models;

use App\Models\Pegawai;
use App\Models\Honor_Fakultas;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gaji_Fakultas extends Model
{
    use HasFactory, SoftDeletes;
       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "gaji_fakultas";
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'pegawai_id',
        'tj_tambahan',
        'honor_kinerja',
        'honor_klb_mengajar',
        'honor_mengajar_DPK',
        'peny_honor_mengajar',
        'tj_guru_besar',
        'total_gaji_fakultas',

    ];
    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }
    public function honorfakultastambahan(){
        return $this->hasMany(Honor_Fakultas_Tambahan::class,'gaji_fakultas_id', 'id');
    }

    public function total_gaji_fakultas($request){
        $tj_tambahan = $request-> tj_tambahan;
        $honor_kinerja = $request-> honor_kinerja;
        $honor_klb_mengajar = $request-> honor_klb_mengajar;
        $honor_mengajar_DPK = $request-> honor_mengajar_DPK;
        $peny_honor_mengajar = $request-> peny_honor_mengajar;
        $tj_guru_besar = $request-> tj_guru_besar;
        $total_gaji_fakultas = $tj_tambahan+$honor_kinerja+$honor_klb_mengajar+$honor_mengajar_DPK+$peny_honor_mengajar+$tj_guru_besar;

        return $total_gaji_fakultas;
    }

}
