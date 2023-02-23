<?php

namespace App\Models;

use App\Models\Karyawan;
use App\Models\Dosen_Tetap;
use App\Models\Dosen_LuarBiasa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Total_Pendapatan extends Model
{
    use HasFactory, SoftDeletes;

            /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jumlah_pendapatan',
        'jumlah_potongan',
        'pendapatan_bersih'
    ];

    public function dosenluarbiasa(){
        return $this->hasMany(Dosen_LuarBiasa::class);
    }

    public function dosentetap(){
        return $this->hasMany(Dosen_Tetap::class);
    }

    public function karyawan(){
        return $this->hasMany(Karyawan::class);
    }
}
