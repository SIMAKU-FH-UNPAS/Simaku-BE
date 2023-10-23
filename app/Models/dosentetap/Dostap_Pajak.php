<?php
namespace App\Models\dosentetap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosentetap\Dostap_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Pajak extends Model
{
    use HasFactory, SoftDeletes;


        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "dostap_pajak";

    protected $fillable = [
        'pensiun',
        'bruto_pajak',
        'bruto_murni',
        'biaya_jabatan',
        'aksa_mandiri',
        'dplk_pensiun',
        'jumlah_potongan_kena_pajak',
        'jumlah_set_potongan_kena_pajak',
        'ptkp',
        'pkp',
        'pajak_pph21',
        'jumlah_set_pajak',
        'potongan_tak_kena_pajak',
        'pendapatan_bersih',
    ];

    public function master_transaksi(){
        return $this->hasOne(Dostap_Master_Transaksi::class,'dostap_pajak_id', 'id');
    }

}
