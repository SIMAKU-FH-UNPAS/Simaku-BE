<?php

namespace App\Models\dosenlb;

use App\Models\dosenlb\Doslb_Potongan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doslb_Potongan_Tambahan extends Model
{
    use HasFactory,SoftDeletes;

    /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

   public $table = "doslb_potongan_tambahan";
   protected $primaryKey = 'id';
   protected $dates = ['deleted_at'];
   protected $fillable = [
       'doslb_potongan_id',
       'nama_potongan',
       'besar_potongan'
   ];

   public function potongan(){
       return $this->belongsTo(Doslb_Potongan::class, 'doslb_potongan_id', 'id');
   }
}
