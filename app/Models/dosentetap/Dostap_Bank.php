<?php

namespace App\Models\dosentetap;

use App\Models\dosentetap\Dosen_Tetap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosentetap\Dostap_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Bank extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "dostap_bank";
    protected $fillable = [
        'nama_bank',
        'no_rekening',
        'dosen_tetap_id'
    ];


    public function dosen_tetap(){
        return $this->belongsTo(Dosen_Tetap::class,'dosen_tetap_id', 'id');
    }
    public function master_transaksi(){
        return $this->hasMany(Dostap_Master_Transaksi::class,'dostap_bank_id', 'id');
    }
}
