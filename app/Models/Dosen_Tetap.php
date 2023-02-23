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
        'nama_dostap',
        'no_pegawai_dostap',
        'golongan_dostap',
        'status_dostap',
        'jabatan_dostap',
        'alamat_KTP_dostap',
        'alamat_saatini_dostap',
        'nama_bank_dostap',
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
