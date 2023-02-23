<?php

namespace App\Models;

use App\Models\Pajak;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pajak_Tambahan extends Model
{
    use HasFactory, SoftDeletes;


        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pajak',
        'besar_pajak'
    ];

    public function pajak()
    {
        return $this->hasMany(Pajak::class);
    }
}
