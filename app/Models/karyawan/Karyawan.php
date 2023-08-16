<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan_Pajak;
use App\Models\karyawan\Karyawan_Potongan;
use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use App\Models\karyawan\Karyawan_Gaji_Universitas;
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

    public $table = "karyawan";
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


    public function gaji_universitas(){
        return $this->hasMany(Karyawan_Gaji_Universitas::class,'karyawan_id', 'id');
    }

    public function gaji_fakultas(){
        return $this->hasMany(Karyawan_Gaji_Fakultas::class,'karyawan_id', 'id');
    }
    public function pajak(){
        return $this->hasMany(Karyawan_Pajak::class,'karyawan_id', 'id');
    }
    public function potongan(){
        return $this->hasMany(Karyawan_Potongan::class,'karyawan_id', 'id');
    }
}
