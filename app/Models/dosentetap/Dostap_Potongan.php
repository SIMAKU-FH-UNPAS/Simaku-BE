<?php

namespace App\Models\dosentetap;

use App\Models\dosentetap\Dostap_Pajak;
use App\Models\dosentetap\Dosen_tetap;
use App\Models\dosentetap\Dostap_Potongan_Tambahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Potongan extends Model
{
    use HasFactory, SoftDeletes;

           /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "dostap_potongan";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'dosen_tetap_id',
        'sp_FH',
        'infaq',
        'total_potongan'
    ];

    public function dosen_tetap(){
        return $this->belongsTo(Dosen_tetap::class, 'dosen_tetap_id', 'id');
    }
    public function potongantambahan(){
        return $this->hasMany(Dostap_Potongan_Tambahan::class,'dostap_potongan_id', 'id');
    }
    public function pajak(){
        return $this->hasOne(Dostap_Pajak::class,'dostap_potongan_id', 'id');
    }

    public function total_potongan($request){
            $sp_FH = $request-> sp_FH;
            $infaq = $request-> infaq;
            $total_potongan = $sp_FH+$infaq;

            return $total_potongan;
        }

}
