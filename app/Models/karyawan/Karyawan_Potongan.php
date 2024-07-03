<?php

namespace App\Models\karyawan;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
protected $guarded = [
    'id'
];
protected $fillable = [
 'potongan'
];

public function master_transaksi(){
    return $this->hasOne(Karyawan_Master_Transaksi::class,'karyawan_potongan_id', 'id');
}
}
