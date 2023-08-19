<?php

namespace App\Models\dosenlb;

use App\Models\dosenlb\Doslb_Potongan;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use App\Models\dosenlb\Doslb_Pajak;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'status',
        'golongan',
        'jabatan',
        'alamat_KTP',
        'alamat_saatini',
        'nama_bank',
        'norek_bank',
        'nomor_hp'
    ];


    public function komponen_pendapatan(){
        return $this->hasMany(Doslb_Komponen_Pendapatan::class,'dosen_luar_biasa_id', 'id');
    }
    public function pajak(){
        return $this->hasMany(Doslb_Pajak::class,'dosen_luar_biasa_id', 'id');
    }
    public function potongan(){
        return $this->hasMany(Doslb_Potongan::class,'dosen_luar_biasa_id', 'id');
    }
}
