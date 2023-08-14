<?php

namespace App\Models\dosentetap;

use App\Models\dosentetap\Dostap_Gaji_Fakultas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Honor_Fakultas extends Model
{
      use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "dostap_honor_fakultas";
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'dostap_gaji_fakultas_id',
        'nama_honor_FH',
        'besar_honor_FH'
    ];

    public function gajifakultas(){
        return $this->belongsTo(Dostap_Gaji_Fakultas::class, 'dostap_gaji_fakultas_id', 'id');
    }


}
