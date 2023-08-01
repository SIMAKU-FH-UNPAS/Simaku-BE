<?php

namespace App\Models;

use App\Models\Pajak;
use App\Models\Golongan;
use App\Models\Gaji_Fakultas;
use App\Models\Gaji_Universitas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "pegawai";
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'no_pegawai',
        'status',
        'posisi',
        'golongan',
        'jabatan',
        'alamat_KTP',
        'alamat_saatini',
        'nama_bank',
        'norek_bank'

    ];


    public function gaji_universitas(){
        return $this->hasMany(Gaji_Universitas::class,'pegawai_id', 'id');
    }

    public function gaji_fakultas(){
        return $this->hasMany(Gaji_Fakultas::class,'pegawai_id', 'id' );
    }
    public function pajak(){
        return $this->hasMany(Pajak::class);
    }
    public function total_pendapatan(){
        return $this->hasMany(Total_Pendapatan::class);
    }
}
