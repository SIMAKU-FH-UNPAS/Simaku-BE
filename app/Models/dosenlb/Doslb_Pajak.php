<?php

namespace App\Models\dosenlb;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosenlb\Doslb_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doslb_Pajak extends Model
{
    use HasFactory, SoftDeletes;


    /**
 * The attributes that are mass assignable.
 *
 * @var array<int, string>
 */
public $table = "doslb_pajak";

protected $fillable = [
    'pajak_pph25',
    'pendapatan_bersih',
];

public function master_transaksi(){
    return $this->hasOne(Doslb_Master_Transaksi::class,'doslb_pajak_id', 'id');
}
}

