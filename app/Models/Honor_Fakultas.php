<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Honor_Fakultas extends Model
{
    use HasFactory, SoftDeletes;


        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jenis_honor_fakultas',
        'gaji_honor_fakultas'
    ];

    public function gajifakultas()
    {
        return $this->hasMany(Gaji_Fakultas::class);
    }
}
