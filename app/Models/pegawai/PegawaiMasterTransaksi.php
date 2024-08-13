<?php

namespace App\Models\pegawai;

use App\Models\master\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PegawaiMasterTransaksi extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'pegawais_id',
        'pegawais_bank_id',
        'status_bank',
        'gaji_date_start',
        'gaji_date_end',
        'pegawais_komponen_pendapatan_id',
        'pegawais_gaji_univ_id',
        'pegawais_gaji_fakultas_id',
        'pegawais_potongan_id',
        'pegawais_pajak_id',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawais_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(PegawaiBank::class, 'pegawais_bank_id', 'id');
    }

    public function gaji_universitas()
    {
        return $this->belongsTo(PegawaiGajiUniv::class, 'pegawais_gaji_univ_id', 'id');
    }

    public function gaji_fakultas()
    {
        return $this->belongsTo(PegawaiGajiFakultas::class, 'pegawais_gaji_fakultas_id', 'id');
    }

    public function potongan()
    {
        return $this->belongsTo(PegawaiPotongan::class, 'pegawais_potongan_id', 'id');
    }

    public function pajak()
    {
        return $this->belongsTo(PegawaiPajak::class, 'pegawais_pajak_id', 'id');
    }

    public function komponen_pendapatan()
    {
        return $this->belongsTo(PegawaiKomponenPendapatan::class, 'pegawais_komponen_pendapatan_id', 'id');
    }
}
