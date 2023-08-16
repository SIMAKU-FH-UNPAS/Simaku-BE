<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan_Pajak;
use App\Models\karyawan\Karyawan;
use App\Models\karyawan\Karyawan_Potongan_Tambahan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan_Potongan extends Model
{
    use HasFactory, SoftDeletes;

    /**
* The attributes that are mass assignable.
*
* @var array<int, string>
*/
public $table = "karyawan_potongan";
protected $dates = ['deleted_at'];
protected $fillable = [
 'sp_FH',
 'infaq',
 'total_potongan',
 'karyawan_id'
];

public function karyawan(){
    return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
}
public function potongantambahan(){
 return $this->hasMany(Karyawan_Potongan_Tambahan::class,'karyawan_potongan_id', 'id');
}
public function pajak(){
 return $this->hasOne(Karyawan_Pajak::class,'karyawan_potongan_id', 'id');
}

public function total_potongan($request){
     $sp_FH = $request-> sp_FH;
     $infaq = $request-> infaq;
     $total_potongan = $sp_FH+$infaq;

     return $total_potongan;
 }

}
