<?php

namespace App\Models\karyawan;

use App\Models\karyawan\Karyawan;
use App\Models\karyawan\Karyawan_Bank;
use App\Models\karyawan\Karyawan_Pajak;
use Illuminate\Database\Eloquent\Model;
use App\Models\karyawan\Karyawan_Potongan;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use App\Models\karyawan\Karyawan_Gaji_Universitas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Karyawan_Master_Transaksi extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "karyawan_master_transaksi";
    protected $fillable = [
        'karyawan_id',
        'karyawan_bank_id',
        'status_bank',
        'gaji_date_start',
        'gaji_date_end',
        'karyawan_gaji_universitas_id',
        'karyawan_gaji_fakultas_id',
        'karyawan_potongan_id',
        'karyawan_pajak_id',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }
    public function bank()
    {
        return $this->belongsTo(Karyawan_Bank::class, 'karyawan_bank_id', 'id');
    }
    public function gaji_universitas()
    {
        return $this->belongsTo(Karyawan_Gaji_Universitas::class, 'karyawan_gaji_universitas_id', 'id');
    }
    public function gaji_fakultas()
    {
        return $this->belongsTo(Karyawan_Gaji_Fakultas::class, 'karyawan_gaji_fakultas_id', 'id');
    }
    public function potongan()
    {
        return $this->belongsTo(Karyawan_Potongan::class, 'karyawan_potongan_id', 'id');
    }
    public function pajak()
    {
        return $this->belongsTo(Karyawan_Pajak::class, 'karyawan_pajak_id', 'id');
    }
}
