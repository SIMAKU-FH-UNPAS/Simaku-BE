<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Potongan extends Model
{
    use HasFactory, SoftDeletes;

           /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "potongan";
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'pegawai_id',
        'sp_FH',
        'iiku',
        'iid',
        'infaq',
        'abt',
        'total_potongan'
    ];

    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }
    public function potongantambahan(){
        return $this->hasMany(Potongan_Tambahan::class,'potongan_id', 'id');
    }

    public function total_potongan($request){
            $sp_FH = $request-> sp_FH;
            $iiku = $request-> iiku;
            $iid = $request-> iid;
            $infaq = $request-> infaq;
            $abt = $request-> abt;
            $total_potongan = $sp_FH+$iiku+$iid+$infaq+$abt;

            return $total_potongan;
        }

}
