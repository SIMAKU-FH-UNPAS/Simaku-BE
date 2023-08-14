<?php

namespace App\Models\dosentetap;

use App\Models\dosentetap\Dostap_Potongan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Potongan_Tambahan extends Model
{
    use HasFactory,SoftDeletes;

    /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

   public $table = "dostap_potongan_tambahan";
   protected $primaryKey = 'id';
   protected $dates = ['deleted_at'];
   protected $fillable = [
       'dostap_potongan_id',
       'nama_potongan',
       'besar_potongan'
   ];

   public function potongan(){
       return $this->belongsTo(Dostap_Potongan::class, 'dostap_potongan_id', 'id');
   }
}
