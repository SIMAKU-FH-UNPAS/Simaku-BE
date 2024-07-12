<?php

namespace App\Models\dosenlb;

use App\Models\dosenlb\Doslb_Bank;
use App\Models\dosenlb\Doslb_Pajak;
use App\Models\dosenlb\Doslb_Potongan;
use Illuminate\Database\Eloquent\Model;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Doslb_Master_Transaksi extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "doslb_master_transaksi";
    protected $fillable = [
        'dosen_luar_biasa_id',
        'doslb_bank_id',
        'status_bank',
        'gaji_date_start',
        'gaji_date_end',
        'doslb_komponen_pendapatan_id',
        'doslb_potongan_id',
        'doslb_pajak_id',
    ];
    public function dosen_luar_biasa()
    {
        return $this->belongsTo(Dosen_Luar_Biasa::class, 'dosen_luar_biasa_id', 'id');
    }
    public function bank()
    {
        return $this->belongsTo(Doslb_Bank::class, 'doslb_bank_id', 'id');
    }
    public function komponen_pendapatan()
    {
        return $this->belongsTo(Doslb_Komponen_Pendapatan::class, 'doslb_komponen_pendapatan_id', 'id');
    }
    public function potongan()
    {
        return $this->belongsTo(Doslb_Potongan::class, 'doslb_potongan_id', 'id');
    }
    public function pajak()
    {
        return $this->belongsTo(Doslb_Pajak::class, 'doslb_pajak_id', 'id');
    }
}
