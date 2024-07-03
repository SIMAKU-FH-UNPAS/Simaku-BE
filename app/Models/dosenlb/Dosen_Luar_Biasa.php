<?php

namespace App\Models\dosenlb;

use App\Models\dosenlb\Doslb_Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosenlb\Doslb_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen_Luar_Biasa extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "dosen_luar_biasa";
    protected $fillable = [
        'nama',
        'no_pegawai',
        'npwp',
        'status',
        'golongan',
        'jabatan',
        'alamat_KTP',
        'alamat_saat_ini',
        'nomor_hp'
    ];

    public function banks(){
        return $this->hasMany(Doslb_Bank::class,'dosen_luar_biasa_id', 'id');
    }
    public function master_transaksi(){
        return $this->hasMany(Doslb_Master_Transaksi::class,'dosen_luar_biasa_id', 'id');
    }
}
