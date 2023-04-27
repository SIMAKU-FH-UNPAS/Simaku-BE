<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Potongan extends Model
{
    use HasFactory, SoftDeletes;

           /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jenis_potongan',
        'besar_potongan',
        'pegawai_id'
    ];

    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }
}
