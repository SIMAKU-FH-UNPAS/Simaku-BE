<?php

namespace App\Models\pegawai;

use App\Models\master\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class KinerjaTambahan extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawais_id', 'id');
    }
}
