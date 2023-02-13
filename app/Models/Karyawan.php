<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
