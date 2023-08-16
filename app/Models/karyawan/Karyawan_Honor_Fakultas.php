<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan_Honor_Fakultas extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "karyawan_honor_fakultas";
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'nama_honor_FH',
        'besar_honor_FH',
        'karyawan_gaji_fakultas_id'
    ];

    public function gajifakultas(){
        return $this->belongsTo(Karyawan_Gaji_Fakultas::class, 'karyawan_gaji_fakultas_id', 'id');
    }


}
