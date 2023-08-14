<?php

namespace App\Models\dosentetap;

use App\Models\dosentetap\Dostap_Pajak;
use App\Models\dosentetap\Dosen_Tetap;
use App\Models\dosentetap\Dostap_Honor_fakultas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Gaji_Fakultas extends Model
{
    use HasFactory, SoftDeletes;
       /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "dostap_gaji_fakultas";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'dosen_tetap_id',
        'tj_tambahan',
        'honor_kinerja',
        'honor_klb_mengajar',
        'honor_mengajar_DPK',
        'peny_honor_mengajar',
        'tj_guru_besar',
        'honor',
        'total_gaji_fakultas',

    ];
    public function dosen_tetap(){
        return $this->belongsTo(Dosen_Tetap::class, 'dosen_tetap_id', 'id');
    }
    public function honorfakultastambahan(){
        return $this->hasMany(Dostap_Honor_Fakultas::class,'dostap_gaji_fakultas_id', 'id');
    }
    public function pajak(){
        return $this->hasOne(Dostap_Pajak::class,'dostap_gaji_fakultas_id', 'id');
    }

    public function total_gaji_fakultas($request){
        $tj_tambahan = $request-> tj_tambahan;
        $honor_kinerja = $request-> honor_kinerja;
        $honor_klb_mengajar = $request-> honor_klb_mengajar;
        $honor_mengajar_DPK = $request-> honor_mengajar_DPK;
        $peny_honor_mengajar = $request-> peny_honor_mengajar;
        $tj_guru_besar = $request-> tj_guru_besar;
        $honor = $request-> honor;
        $total_gaji_fakultas = $tj_tambahan+$honor_kinerja+$honor_klb_mengajar+$honor_mengajar_DPK+$peny_honor_mengajar+$tj_guru_besar+$honor;

        return $total_gaji_fakultas;
    }

}
