<?php

namespace App\Models;

use App\Models\Pegawai;
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
        'pendapatan_bersih',
        'pegawai_id'
    ];

    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }
}
