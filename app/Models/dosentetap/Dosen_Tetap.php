<?php

namespace App\Models\dosentetap;

use App\Models\dosentetap\Dostap_Pajak;
use App\Models\dosentetap\Dostap_Potongan;
use App\Models\dosentetap\Dostap_Gaji_Fakultas;
use App\Models\dosentetap\Dostap_Gaji_Universitas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen_Tetap extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "dosen_tetap";
    protected $fillable = [
        'nama',
        'no_pegawai',
        'npwp',
        'status',
        'golongan',
        'jabatan',
        'alamat_KTP',
        'alamat_saatini',
        'nama_bank_utama',
        'nama_bank_tambahan',
        'norek_bank_utama',
        'norek_bank_tambahan',
        'nomor_hp'
    ];


    public function gaji_universitas(){
        return $this->hasMany(Dostap_Gaji_Universitas::class,'dosen_tetap_id', 'id');
    }

    public function gaji_fakultas(){
        return $this->hasMany(Dostap_Gaji_Fakultas::class,'dosen_tetap_id', 'id');
    }
    public function pajak(){
        return $this->hasMany(Dostap_Pajak::class,'dosen_tetap_id', 'id');
    }
    public function potongan(){
        return $this->hasMany(Dostap_Potongan::class,'dosen_tetap_id', 'id');
    }
    public function total_pendapatan(){
        return $this->hasMany(Total_Pendapatan::class);
    }
}
