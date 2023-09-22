<?php

namespace App\Models\dosenlb;

use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doslb_Komponen_Pendapatan_Tambahan extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = "doslb_komponen_pendapatan_tambahan";
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'doslb_pendapatan_id',
        'nama_komponen',
        'besar_komponen'
    ];

    public function komponen_pendapatan(){
        return $this->belongsTo(Doslb_Komponen_Pendapatan::class, 'doslb_pendapatan_id', 'id');
    }
}
