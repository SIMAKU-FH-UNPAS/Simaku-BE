<?php

namespace App\Models\dosentetap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosentetap\Dostap_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Dostap_Gaji_Fakultas extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "dostap_gaji_fakultas";
    protected $dates = ['deleted_at'];
    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'gaji_fakultas'
    ];

    public function master_transaksi()
    {
        return $this->hasOne(Dostap_Master_Transaksi::class, 'dostap_gaji_fakultas_id', 'id');
    }
}
