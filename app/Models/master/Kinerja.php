<?php

namespace App\Models\master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kinerja extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'tgl_awal',
        'tgl_akhir',
    ];
}
