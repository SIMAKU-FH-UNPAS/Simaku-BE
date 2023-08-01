<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pajak_Tambahan extends Model
{
    use HasFactory,SoftDeletes;

      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     public $table = "pajak_tambahan";
     protected $primaryKey = 'id';
     protected $dates = ['deleted_at'];
     protected $fillable = [
         'pajak_id',
         'nama_pajak',
         'besar_pajak'
     ];

     public function pajak(){
         return $this->belongsTo(Pajak::class, 'pajak_id', 'id');
     }

}
