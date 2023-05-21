<?php

namespace App\Http\Controllers\API;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\Gaji_Fakultas;
use App\Models\Gaji_Universitas;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class RakapitulasiController extends Controller
{
    public function total_gaji(){
        $pegawaiAll = Pegawai::all();
        foreach($pegawaiAll as $pegawai) {
        $gajiuniv = Gaji_Universitas::where('pegawai_id', $pegawai->id)->first();
        $gajifakultas = Gaji_Fakultas::where('pegawai_id', $pegawai->id)->first();
        $total_gaji[] = (5/100)*$gajifakultas['total_gaji_fakultas'] + $gajiuniv['total_gaji_univ'];
        }
        return ResponseFormatter::success($total_gaji);
    }
}
