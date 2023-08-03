<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Potongan_Tambahan extends Model
{
    use HasFactory,SoftDeletes;

    /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

   public $table = "potongan_tambahan";
   protected $primaryKey = 'id';
   protected $dates = ['deleted_at'];
   protected $fillable = [
       'potongan_id',
       'nama_potongan',
       'besar_potongan'
   ];

   public function potongan(){
       return $this->belongsTo(Potongan::class, 'potongan_id', 'id');
   }
}
