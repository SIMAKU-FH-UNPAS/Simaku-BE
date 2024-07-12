<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Karyawan_Bank extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "karyawan_bank";
    protected $fillable = [
        'nama_bank',
        'no_rekening',
        'karyawan_id'
    ];


    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
    public function master_transaksi()
    {
        return $this->hasMany(Karyawan_Master_Transaksi::class, 'karyawan_bank_id', 'id');
    }
}
