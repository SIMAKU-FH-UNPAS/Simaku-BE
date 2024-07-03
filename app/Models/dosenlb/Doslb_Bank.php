<?php

namespace App\Models\dosenlb;

use Illuminate\Database\Eloquent\Model;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosenlb\Doslb_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doslb_Bank extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "doslb_bank";
    protected $fillable = [
        'nama_bank',
        'no_rekening',
        'dosen_luar_biasa_id'
    ];

    public function dosen_luar_biasa(){
        return $this->belongsTo(Dosen_Luar_Biasa::class,'dosen_luar_biasa_id', 'id');
    }
    public function master_transaksi(){
        return $this->hasMany(Doslb_Master_Transaksi::class,'doslb_bank_id', 'id');
    }
}
