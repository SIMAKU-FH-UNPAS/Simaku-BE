<?php

namespace App\Models\pegawai;

use App\Models\master\Kinerja;
use App\Models\master\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class KinerjaTambahan extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'pegawais_id',
        'kinerjas_id',
        'tgl_awal',
        'tgl_akhir',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawais_id', 'id');
    }

    public function kinerja()
    {
        return $this->belongsTo(Kinerja::class, 'kinerjas_id', 'id');
    }
}
