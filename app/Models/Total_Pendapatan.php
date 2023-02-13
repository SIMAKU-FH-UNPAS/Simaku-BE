<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
