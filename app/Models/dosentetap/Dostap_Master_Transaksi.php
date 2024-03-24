<?php

namespace App\Models\dosentetap;

use App\Models\dosentetap\Dosen_Tetap;
use App\Models\dosentetap\Dostap_Bank;
use App\Models\dosentetap\Dostap_Pajak;
use Illuminate\Database\Eloquent\Model;
use App\Models\dosentetap\Dostap_Potongan;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosentetap\Dostap_Gaji_Fakultas;
use App\Models\dosentetap\Dostap_Gaji_Universitas;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Master_Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "dostap_master_transaksi";
    protected $fillable = [
        'dosen_tetap_id',
        'dostap_bank_id',
        'status_bank',
        'gaji_date_start',
        'gaji_date_end',
        'dostap_gaji_universitas_id',
        'dostap_gaji_fakultas_id',
        'dostap_potongan_id',
        'dostap_pajak_id',
    ];

    public function dosen_tetap(){
        return $this->belongsTo(Dosen_Tetap::class,'dosen_tetap_id', 'id');
    }
    public function bank(){
        return $this->belongsTo(Dostap_Bank::class,'dostap_bank_id', 'id');
    }
    public function gaji_universitas(){
        return $this->belongsTo(Dostap_Gaji_Universitas::class,'dostap_gaji_universitas_id', 'id');
    }
    public function gaji_fakultas(){
        return $this->belongsTo(Dostap_Gaji_Fakultas::class,'dostap_gaji_fakultas_id', 'id');
    }
    public function potongan(){
        return $this->belongsTo(Dostap_Potongan::class,'dostap_potongan_id', 'id');
    }
    public function pajak(){
        return $this->belongsTo(Dostap_Pajak::class,'dostap_pajak_id', 'id');
    }
}
