<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan_Potongan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan_Potongan_Tambahan extends Model
{
    use HasFactory,SoftDeletes;

    /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

   public $table = "karyawan_potongan_tambahan";
   protected $primaryKey = 'id';
   protected $dates = ['deleted_at'];
   protected $fillable = [
       'nama_potongan',
       'besar_potongan',
       'karyawan_potongan_id'
   ];

   public function potongan(){
       return $this->belongsTo(Karyawan_Potongan::class, 'karyawan_potongan_id', 'id');
   }
}
