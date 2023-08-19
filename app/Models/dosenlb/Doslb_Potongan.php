<?php

namespace App\Models\dosenlb;

use App\Models\dosenlb\Doslb_Pajak;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use App\Models\dosenlb\Doslb_Potongan_Tambahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doslb_Potongan extends Model
{
    use HasFactory, SoftDeletes;

    /**
* The attributes that are mass assignable.
*
* @var array<int, string>
*/
public $table = "doslb_potongan";
protected $dates = ['deleted_at'];
protected $fillable = [
 'dosen_luar_biasa_id',
 'sp_FH',
 'infaq',
 'total_potongan'
];

public function dosen_luar_biasa(){
    return $this->belongsTo(Dosen_Luar_Biasa::class, 'dosen_luar_biasa_id', 'id');
}
public function potongantambahan(){
 return $this->hasMany(Doslb_Potongan_Tambahan::class,'doslb_potongan_id', 'id');
}
public function pajak(){
 return $this->hasOne(Doslb_Pajak::class,'doslb_potongan_id', 'id');
}

public function total_potongan($request){
     $sp_FH = $request-> sp_FH;
     $infaq = $request-> infaq;
     $total_potongan = $sp_FH+$infaq;

     return $total_potongan;
 }

}
