<?php

namespace App\Http\Controllers\API\dosentetap;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\dosentetap\Dosen_Tetap;
use App\Models\dosentetap\Dostap_Gaji_Fakultas;
use App\Models\dosentetap\Dostap_Honor_Fakultas;
use App\Models\dosentetap\Dostap_Gaji_Universitas;
use App\Models\dosentetap\Dostap_Pajak;
use App\Models\dosentetap\Dostap_Potongan;
use App\Models\dosentetap\Dostap_Potongan_Tambahan;

class LaporanController extends Controller
{
    public function rekapitulasipendapatan(Request $request){
        // Get ALL DosenTetap attributr nama & golongan
        $dosentetapALL = Dosen_Tetap::all();
        $month = $request->input('month');
        $year = $request->input('year');

        // Request by month & year
        if(isset($month) && isset ($year)){
            foreach($dosentetapALL as $dosentetap){
                // Get ALL DATA gaji fakultas with condition
                $gajifakultas = Dostap_Gaji_Fakultas::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            foreach($gajifakultas as $key=> $gajifak){
                    // Get ALL DATA gaji universitas with condition
                $gajiuniv = Dostap_Gaji_Universitas::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                    // Get ALL DATA honor fakultas with condition
                $honorfakultas = Dostap_Honor_Fakultas::where('dostap_gaji_fakultas_id', $gajifak->id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->get();

            // Array Attribute
            $gajiunivarray = !empty($gajiuniv[$key]) ? $gajiuniv[$key]['total_gaji_univ'] : 0;
            $gajifakarray = !empty($gajifak) ? $gajifak['total_gaji_fakultas'] : 0;

            // menampilkan data dalam bentuk array
                $rekapitulasipendapatan[] = [
                    'nama' => Dosen_Tetap::find($dosentetap->id)->nama,
                    'golongan' => Dosen_Tetap::find($dosentetap->id)->golongan,
                    'dostap_gaji_fakultas' => $gajifakultas,
                    'dostap_honor_fakultas' => $honorfakultas,
                    'total_gaji_univ' => $gajiunivarray,
                    'total_pendapatan' => $gajiunivarray + $gajifakarray

                ];
            }
            }
            // Check if the rekapitulasipendapatan array is empty
        if (empty($rekapitulasipendapatan)) {
            return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
        }

        return ResponseFormatter::success($rekapitulasipendapatan, 'Data Laporan Rekapitulasi Pendapatan Dosen Tetap Found');
    }
    }

        public function pendapatanbersih(Request $request){
            $dosentetapALL = Dosen_Tetap::all();
            $month = $request->input('month');
            $year = $request->input('year');

            // Request by month & year
            if(isset($month) && isset ($year)){
                foreach($dosentetapALL as $dosentetap){
                    // Get ALL DATA gaji fakultas with condition
                    $gajifakultas = Dostap_Gaji_Fakultas::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                foreach($gajifakultas as $key=> $gajifak){
                        // Get ALL DATA gaji universitas with condition
                    $gajiuniv = Dostap_Gaji_Universitas::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                    $potongan = Dostap_Potongan::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                    $pajak = Dostap_Pajak::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();


            // Array Attribute
            $gajiunivarray = !empty($gajiuniv[$key]) ? $gajiuniv[$key]['total_gaji_univ'] : 0;
            $gajifakarray = !empty($gajifak) ? $gajifak['total_gaji_fakultas'] : 0;
            $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;
            $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pendapatan_bersih'] : 0;

             // menampilkan data dalam bentuk array
             $pendapatanbersih[] = [
                'nama' => Dosen_Tetap::find($dosentetap->id)->nama,
                'golongan' => Dosen_Tetap::find($dosentetap->id)->golongan,
                'total_gaji_fakultas' => $gajifakarray,
                'total_gaji_univ' => $gajiunivarray,
                'total_pendapatan' => $gajiunivarray + $gajifakarray,
                'total_potongan' => $potonganarray,
                'pendapatan_bersih' => $pajakarray

            ];
        }
    }
        // Check if the pendapatanbersih array is empty
        if (empty($pendapatanbersih)) {
            return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
        }

        return ResponseFormatter::success($pendapatanbersih, 'Data Laporan Pendapatan Bersih Dosen Tetap Found');
}
}

    public function laporanpajak(Request $request){
        $dosentetapALL = Dosen_Tetap::all();
        $month = $request->input('month');
        $year = $request->input('year');

        // Request by month & year
        if(isset($month) && isset ($year)){
            foreach($dosentetapALL as $dosentetap){
                // Get ALL DATA gaji fakultas with condition
                $gajifakultas = Dostap_Gaji_Fakultas::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            foreach($gajifakultas as $key=> $gajifak){
                    // Get ALL DATA gaji universitas with condition
                $gajiuniv = Dostap_Gaji_Universitas::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                $potongan = Dostap_Potongan::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                $pajak = Dostap_Pajak::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();

            // Array Attribute
            $gajiunivarray = !empty($gajiuniv[$key]) ? $gajiuniv[$key]['total_gaji_univ'] : 0;
            $gajifakarray = !empty($gajifak) ? $gajifak['total_gaji_fakultas'] : 0;
            $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;

             // menampilkan data dalam bentuk array
             $laporanpajak[] = [
                'nama' => Dosen_Tetap::find($dosentetap->id)->nama,
                'golongan' => Dosen_Tetap::find($dosentetap->id)->golongan,
                'total_gaji_fakultas' => $gajifakarray,
                'total_gaji_univ' => $gajiunivarray,
                'total_potongan' => $potonganarray,
                'dostap_pajak' => $pajak

                ];
        }
    }
    // Check if the laporanpajak array is empty
    if (empty($laporanpajak)) {
        return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
    }

    return ResponseFormatter::success($laporanpajak, 'Data Laporan Pajak Dosen Tetap Found');
    }
}
            public function laporanpotongan(Request $request){
            // Get ALL DosenTetap attributr nama & golongan
            $dosentetapALL = Dosen_Tetap::all();
            $month = $request->input('month');
            $year = $request->input('year');

            // Request by month & year
            if(isset($month) && isset ($year)){
                foreach($dosentetapALL as $dosentetap){
                    // Get ALL DATA gaji fakultas with condition
                    $potonganfh = Dostap_Potongan::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                foreach($potonganfh as $key=> $potongan){
                        // Get ALL DATA PotonganTambahan with condition
                    $potongantambahan = Dostap_Potongan_Tambahan::where('dostap_potongan_id', $potongan->id)
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->get();

                  // menampilkan data dalam bentuk array
                $laporanpotongan[] = [
                    'nama' => Dosen_Tetap::find($dosentetap->id)->nama,
                    'golongan' => Dosen_Tetap::find($dosentetap->id)->golongan,
                    'dostap_potongan' => $potonganfh,
                    'dostap_potongan_tambahan' => $potongantambahan,
                    ];
                }
                }
                 // Check if the laporanpotongan array is empty
                if (empty($laporanpotongan)) {
                    return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
                }
                return ResponseFormatter::success($laporanpotongan, 'Data Laporan Potongan Dosen Tetap Found');
            }
}

            public function rekapitulasibank(Request $request){
            // Get ALL DosenTetap attributr nama & golongan
            $dosentetapALL = Dosen_Tetap::all();
            $month = $request->input('month');
            $year = $request->input('year');

              // Request by month & year
              if(isset($month) && isset ($year)){
                $rekapitulasibank = []; // Inisialisasi array untuk menampung data
                foreach($dosentetapALL as $dosentetap){
                    // Get ALL DATA
                    $gajiuniversitas = Dostap_Gaji_Universitas::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                    foreach($gajiuniversitas as $key=> $gajiuniv){
                        // Get ALL DATA
                    $gajifakultas = Dostap_Gaji_Fakultas::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                    $potongan = Dostap_Potongan::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                    $pajak = Dostap_Pajak::where('dosen_tetap_id', $dosentetap->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();

                     // Array Attribute
            $gajifakarray = !empty($gajifakultas[$key]) ? $gajifakultas[$key]['total_gaji_fakultas'] : 0;
            $gajiunivarray = !empty($gajiuniv) ? $gajiuniv['total_gaji_univ'] : 0;
            $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;
            $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pendapatan_bersih'] : 0;
                 // menampilkan data dalam bentuk array
                 $data = [
                    'nama' => Dosen_Tetap::find($dosentetap->id)->nama,
                    'golongan' => Dosen_Tetap::find($dosentetap->id)->golongan,
                    'total_pendapatan' => $gajiunivarray + $gajifakarray,
                    'total_potongan' => $potonganarray,
                    'pendapatan_bersih' => $pajakarray,
                    'no_rekening' => Dosen_Tetap::find($dosentetap->id)->norek_bank,
                    'nama_bank' => Dosen_Tetap::find($dosentetap->id)->nama_bank
                    ];

                // Memilah data berdasarkan nama_bank
                if ( Dosen_Tetap::find($dosentetap->id)->nama_bank === 'Mandiri') {
                        $rekapitulasibank['payroll'][] = $data;
                } else {
                        $rekapitulasibank['non_payroll'][] = $data;
                }
            }
        }
          // Check if the laporanpotongan array is empty
          if (empty($rekapitulasibank)) {
            return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
        }
        return ResponseFormatter::success($rekapitulasibank, 'Data Laporan Rekapitulasi Bank Dosen Tetap Found');
        }

    }
}

