<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Honor_Fakultas_Tambahan extends Model
{
      use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "honor_fakultas_tambahan";
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'gaji_fakultas_id',
        'nama_honor_FH',
        'besar_honor_FH'
    ];

    public function gajifakultas(){
        return $this->belongsTo(Gaji_Fakultas::class, 'gaji_fakultas_id', 'id');
    }


}
