<?php

namespace App\Models;

use App\Models\Pajak;
use App\Models\Potongan;
use App\Models\Gaji_Fakultas;
use App\Models\Gaji_Universitas;
use App\Models\Total_Pendapatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen_LuarBiasa extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "dosen_luarbiasa";
    protected $fillable = [
        'nama_dosluar',
        'no_pegawai_dosluar',
        'golongan_dosluar',
        'status_dosluar',
        'jabatan_dosluar',
        'alamat_KTP_dosluar',
        'alamat_saatini_dosluar',
        'nama_bank_dosluar',
        'total_pendapatan_id'
    ];


    public function gajiuniv(){
        return $this->hasMany(Gaji_Universitas::class);
    }


    public function gajifak(){
        return $this->hasMany(Gaji_Fakultas::class);
    }

    public function pajak(){
        return $this->hasMany(Pajak::class);
    }
    public function potongan(){
        return $this->hasMany(Potongan::class);
    }

    public function totalpendapatan()
    {
        return $this->belongsTo(Total_Pendapatan::class);
    }
}
