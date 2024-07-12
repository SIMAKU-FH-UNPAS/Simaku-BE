<?php

namespace App\Models\karyawan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Karyawan_Gaji_Fakultas extends Model implements Auditable
{
   use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;
   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   public $table = "karyawan_gaji_fakultas";
   protected $dates = ['deleted_at'];
   protected $guarded = [
      'id'
   ];
   protected $fillable = [
      'gaji_fakultas',

   ];

   public function master_transaksi()
   {
      return $this->hasOne(Karyawan_Master_Transaksi::class, 'karyawan_gaji_fakultas_id', 'id');
   }
}
