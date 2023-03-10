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

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "karyawan";
    protected $fillable = [
        'nama_karyawan',
        'no_pegawai_karyawan',
        'golongan_karyawan',
        'status_karyawan',
        'jabatan_karyawan',
        'alamat_KTP_karyawan',
        'alamat_saatini_karyawan',
        'nama_bank_karyawan',
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
