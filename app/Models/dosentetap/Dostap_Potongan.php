<?php

namespace App\Models\dosentetap;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosentetap\Dostap_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dostap_Potongan extends Model
{
    use HasFactory, SoftDeletes;

           /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "dostap_potongan";
    protected $dates = ['deleted_at'];
    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'potongan'
    ];

    public function master_transaksi(){
        return $this->hasOne(Dostap_Master_Transaksi::class,'dostap_potongan_id', 'id');
    }

}
