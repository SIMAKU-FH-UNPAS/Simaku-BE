<?php

namespace App\Http\Controllers\API\dosentetap;

use App\Models\dosentetap\Dostap_Pajak;
use App\Models\dosentetap\Dosen_Tetap;
use App\Models\dosentetap\Dostap_Gaji_Fakultas;
use App\Models\dosentetap\Dostap_Gaji_Universitas;
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
        $dosentetapAll = Dosen_Tetap::where('posisi', $request->posisi)->get();
        if(isset($request->year)){
        foreach($dosentetapAll as $dosentetap) {
        $gajiuniv = Dostap_Gaji_Universitas::where('dosen_tetap_id', $dosentetap->id)->whereYear('created_at', $request->year)->whereMonth('created_at', $request->month)->get();
        foreach($gajiuniv as $key=>$gaji){
            $gajifakultas = Dostap_Gaji_Fakultas::where('dosen_tetap_id', $dosentetap->id)->whereYear('created_at', $request->year)->whereMonth('created_at', $request->month)->get();
            $pajak = Dostap_Pajak::where('dosen_tetap_id', $dosentetap->id)->whereYear('created_at', $request->year)->whereMonth('created_at', $request->month)->get();

        // Array Attribute
            $gajifakultasarray = !empty($gajifakultas[$key]) ? $gajifakultas[$key]['total_gaji_fakultas'] : 0;
            $gajiunivarray = !empty($gaji) ? $gaji['total_gaji_univ'] : 0;
            $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pensiun'] : 0;
        // Perhitungan Bruto
            $pendapatanbruto[] = ['nama'=>Dosen_Tetap::find($dosentetap->id)->nama, 'posisi'=>Dosen_Tetap::find($dosentetap->id)->posisi, 'pendapatan_bruto'=>$gajifakultasarray  + $gajiunivarray + $pajakarray];
        }
        return ResponseFormatter::success($pendapatanbruto);
    }

    }else{

        // Get Multiple Data
            $gajiuniv = Dostap_Gaji_Universitas::get();
            $gajifakultas = Dostap_Gaji_Fakultas::get();
            $pajak = Dostap_Pajak::get();

            $dosentetapAll = Dosen_Tetap::all();
            foreach($dosentetapAll as $dosentetap) {
                $gajiuniv = Dostap_Gaji_Universitas::where('dosen_tetap_id', $dosentetap->id)->get();
                foreach($gajiuniv as $key=>$gaji){
                    $gajifakultas = Dostap_Gaji_Fakultas::where('dosen_tetap_id', $dosentetap->id)->get();
                    $pajak = Dostap_Pajak::where('dosen_tetap_id', $dosentetap->id)->get();

        //Array Attribute
                    $gajifakultasarray = !empty($gajifakultas[$key]) ? $gajifakultas[$key]['total_gaji_fakultas'] : 0;
                    $gajiunivarray = !empty($gaji) ? $gaji['total_gaji_univ'] : 0;
                    $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pensiun'] : 0;
        //Perhitungan Bruto
                    $pendapatanbruto[] = ['nama'=>Dosen_Tetap::find($dosentetap->id)->nama, 'posisi'=>Dosen_Tetap::find($dosentetap->id)->posisi, 'pendapatan_bruto'=>$gajifakultasarray  + $gajiunivarray + $pajakarray];
                }
            return ResponseFormatter::success($pendapatanbruto);
        }



    }
}
}
