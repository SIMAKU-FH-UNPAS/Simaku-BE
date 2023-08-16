<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan_Pajak;
use App\Models\karyawan\Karyawan;
use App\Models\karyawan\Karyawan_Honor_fakultas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan_Gaji_Fakultas extends Model
{
    use HasFactory, SoftDeletes;
    /**
  * The attributes that are mass assignable.
  *
  * @var array<int, string>
  */
 public $table = "karyawan_gaji_fakultas";
 protected $dates = ['deleted_at'];
 protected $fillable = [
     'tj_tambahan',
     'honor_kinerja',
     'honor',
     'total_gaji_fakultas',
     'karyawan_id'

 ];
 public function karyawan(){
     return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
 }
 public function honorfakultastambahan(){
     return $this->hasMany(Karyawan_Honor_Fakultas::class,'karyawan_gaji_fakultas_id', 'id');
 }
 public function pajak(){
     return $this->hasOne(Karyawan_Pajak::class,'karyawan_gaji_fakultas_id', 'id');
 }

 public function total_gaji_fakultas($request){
     $tj_tambahan = $request-> tj_tambahan;
     $honor_kinerja = $request-> honor_kinerja;
     $honor = $request-> honor;
     $total_gaji_fakultas = $tj_tambahan+$honor_kinerja+$honor;

     return $total_gaji_fakultas;
 }
}
