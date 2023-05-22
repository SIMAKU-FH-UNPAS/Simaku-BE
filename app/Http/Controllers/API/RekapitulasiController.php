<?php

namespace App\Http\Controllers\API;

use App\Models\Pajak;
use App\Models\Pegawai;
use App\Models\Gaji_Fakultas;
use App\Models\Gaji_Universitas;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RekapitulasiController extends Controller
{
    // public function pendapatanbruto(){
    //     $pegawaiAll = Pegawai::all();
    //     foreach($pegawaiAll as $pegawai) {
    //     $gajiuniv = Gaji_Universitas::where('pegawai_id', $pegawai->id)->first();
    //     $gajifakultas = Gaji_Fakultas::where('pegawai_id', $pegawai->id)->first();
    //     $pajak = Pajak::where('pegawai_id', $pegawai->id)->first();

    //     $gajifakultasarray = !empty($gajifakultas) ? $gajifakultas['total_gaji_fakultas'] : 0;
    //     $gajiunivarray = !empty($gajiuniv) ? $gajiuniv['total_gaji_univ'] : 0;
    //     $pajakarray = !empty($pajak) ? $pajak['pensiun'] : 0;
    //     $pendapatanbruto[] =  $gajifakultasarray  + $gajiunivarray + $pajakarray;
    //     }
    //     return ResponseFormatter::success($pendapatanbruto);
    // }
    public function pendapatanbruto(Request $request){
        // Get by Attribute Posisi, Year, Month
        $pegawaiAll = Pegawai::where('posisi', $request->posisi)->get();
        if(isset($request->year)){
        foreach($pegawaiAll as $pegawai) {
        $gajiuniv = Gaji_Universitas::where('pegawai_id', $pegawai->id)->whereYear('created_at', $request->year)->whereMonth('created_at', $request->month)->get();
        foreach($gajiuniv as $key=>$gaji){
            $gajifakultas = Gaji_Fakultas::where('pegawai_id', $pegawai->id)->whereYear('created_at', $request->year)->whereMonth('created_at', $request->month)->get();
            $pajak = Pajak::where('pegawai_id', $pegawai->id)->whereYear('created_at', $request->year)->whereMonth('created_at', $request->month)->get();

        // Array Attribute
            $gajifakultasarray = !empty($gajifakultas[$key]) ? $gajifakultas[$key]['total_gaji_fakultas'] : 0;
            $gajiunivarray = !empty($gaji) ? $gaji['total_gaji_univ'] : 0;
            $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pensiun'] : 0;
        // Perhitungan Bruto
            $pendapatanbruto[] = ['nama'=>Pegawai::find($pegawai->id)->nama, 'posisi'=>Pegawai::find($pegawai->id)->posisi, 'pendapatan_bruto'=>$gajifakultasarray  + $gajiunivarray + $pajakarray];
        }
        return ResponseFormatter::success($pendapatanbruto);
    }

    }else{

        // Get Multiple Data
            $gajiuniv = Gaji_Universitas::get();
            $gajifakultas = Gaji_Fakultas::get();
            $pajak = Pajak::get();

            $pegawaiAll = Pegawai::all();
            foreach($pegawaiAll as $pegawai) {
                $gajiuniv = Gaji_Universitas::where('pegawai_id', $pegawai->id)->get();
                foreach($gajiuniv as $key=>$gaji){
                    $gajifakultas = Gaji_Fakultas::where('pegawai_id', $pegawai->id)->get();
                    $pajak = Pajak::where('pegawai_id', $pegawai->id)->get();

        //Array Attribute
                    $gajifakultasarray = !empty($gajifakultas[$key]) ? $gajifakultas[$key]['total_gaji_fakultas'] : 0;
                    $gajiunivarray = !empty($gaji) ? $gaji['total_gaji_univ'] : 0;
                    $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pensiun'] : 0;
        //Perhitungan Bruto
                    $pendapatanbruto[] = ['nama'=>Pegawai::find($pegawai->id)->nama, 'posisi'=>Pegawai::find($pegawai->id)->posisi, 'pendapatan_bruto'=>$gajifakultasarray  + $gajiunivarray + $pajakarray];
                }
            return ResponseFormatter::success($pendapatanbruto);
        }



    }
}
}
