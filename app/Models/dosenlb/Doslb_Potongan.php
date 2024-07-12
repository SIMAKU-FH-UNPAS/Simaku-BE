<?php

namespace App\Models\dosenlb;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\dosenlb\Doslb_Master_Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Doslb_Potongan extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $table = "doslb_potongan";
    protected $dates = ['deleted_at'];
    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'potongan',
    ];

    public function master_transaksi()
    {
        return $this->hasOne(Doslb_Master_Transaksi::class, 'doslb_potongan_id', 'id');
    }
}
