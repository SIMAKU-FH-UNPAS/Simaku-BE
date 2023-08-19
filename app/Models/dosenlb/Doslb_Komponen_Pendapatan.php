<?php

namespace App\Models\dosenlb;

use App\Models\dosenlb\Doslb_Pajak;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan_Tambahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doslb_Komponen_Pendapatan extends Model
{
    use HasFactory, SoftDeletes;
    /**
  * The attributes that are mass assignable.
  *
  * @var array<int, string>
  */
 public $table = "doslb_komponen_pendapatan";
 protected $dates = ['deleted_at'];
 protected $fillable = [
    'dosen_luar_biasa_id',
     'tj_tambahan',
     'honor_kinerja',
     'honor_mengajar',
     'tj_guru_besar',
     'total_komponen_pendapatan',

 ];
 public function dosen_luar_biasa(){
     return $this->belongsTo(Dosen_Luar_Biasa::class, 'dosen_luar_biasa_id', 'id');
 }
 public function komponenpendapatantambahan(){
     return $this->hasMany(Doslb_Komponen_Pendapatan_Tambahan::class,'doslb_pendapatan_id', 'id');
 }
 public function pajak(){
     return $this->hasOne(Doslb_Pajak::class,'doslb_pendapatan_id', 'id');
 }

 public function total_komponen_pendapatan($request){
     $tj_tambahan = $request-> tj_tambahan;
     $honor_kinerja = $request-> honor_kinerja;
     $honor_mengajar = $request-> honor_mengajar;
     $tj_guru_besar = $request-> tj_guru_besar;
     $total_komponen_pendapatan = $tj_tambahan+$honor_kinerja+$honor_mengajar+$tj_guru_besar;

     return $total_komponen_pendapatan;
 }

}
