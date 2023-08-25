<?php

namespace App\Http\Controllers\API\karyawan;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\karyawan\Karyawan;
use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use App\Models\karyawan\Karyawan_Gaji_Universitas;
use App\Models\karyawan\Karyawan_Honor_Fakultas;
use App\Models\karyawan\Karyawan_Pajak;
use App\Models\karyawan\Karyawan_Potongan;
use App\Models\karyawan\Karyawan_Potongan_Tambahan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function rekapitulasipendapatan(Request $request){
       // Get ALL Karyawan attribute by month & year
        $karyawanALL = Karyawan::all();
        $month = $request->input('month');
        $year = $request->input('year');

        // Request by month & year
        if(isset($month) && isset ($year)){
            foreach($karyawanALL as $karyawan){
                // Get ALL DATA gaji fakultas with condition
                $gajifakultas = Karyawan_Gaji_Fakultas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            foreach($gajifakultas as $key=> $gajifak){
                    // Get ALL DATA gaji universitas with condition
                $gajiuniv = Karyawan_Gaji_Universitas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                    // Get ALL DATA honor fakultas with condition
                $honorfakultas = Karyawan_Honor_Fakultas::where('karyawan_gaji_fakultas_id', $gajifak->id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->get();

            // Array Attribute
            $gajiunivarray = !empty($gajiuniv[$key]) ? $gajiuniv[$key]['total_gaji_univ'] : 0;
            $gajifakarray = !empty($gajifak) ? $gajifak['total_gaji_fakultas'] : 0;

            // menampilkan data dalam bentuk array
                $rekapitulasipendapatan[] = [
                    'nama' => Karyawan::find($karyawan->id)->nama,
                    'golongan' => Karyawan::find($karyawan->id)->golongan,
                    'karyawan_gaji_fakultas' => $gajifakultas,
                    'karyawan_honor_fakultas' => $honorfakultas,
                    'total_gaji_univ' => $gajiunivarray,
                    'total_pendapatan' => $gajiunivarray + $gajifakarray

                ];
            }
            }
            // Check if the rekapitulasipendapatan array is empty
        if (empty($rekapitulasipendapatan)) {
            return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
        }

        return ResponseFormatter::success($rekapitulasipendapatan, 'Data Laporan Rekapitulasi Pendapatan Karyawan Found');
    }
    }

        public function pendapatanbersih(Request $request){
              // Get ALL Karyawan attribute by month & year
            $karyawanALL = Karyawan::all();
            $month = $request->input('month');
            $year = $request->input('year');


            // Request by month & year
            if(isset($month) && isset ($year)){
                foreach($karyawanALL as $karyawan){
                    // Get ALL DATA gaji fakultas with condition
                    $gajifakultas = Karyawan_Gaji_Fakultas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                foreach($gajifakultas as $key=> $gajifak){
                        // Get ALL DATA gaji universitas with condition
                    $gajiuniv = Karyawan_Gaji_Universitas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                    $potongan = Karyawan_Potongan::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                    $pajak = Karyawan_Pajak::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();


            // Array Attribute
            $gajiunivarray = !empty($gajiuniv[$key]) ? $gajiuniv[$key]['total_gaji_univ'] : 0;
            $gajifakarray = !empty($gajifak) ? $gajifak['total_gaji_fakultas'] : 0;
            $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;
            $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pendapatan_bersih'] : 0;

             // menampilkan data dalam bentuk array
             $pendapatanbersih[] = [
                'nama' => Karyawan::find($karyawan->id)->nama,
                'golongan' => Karyawan::find($karyawan->id)->golongan,
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

        return ResponseFormatter::success($pendapatanbersih, 'Data Laporan Pendapatan Bersih Karyawan Found');
}
}

    public function laporanpajak(Request $request){
       // Get ALL Karyawan attribute by month & year
        $karyawanALL = Karyawan::all();
        $month = $request->input('month');
        $year = $request->input('year');

        // Request by month & year
        if(isset($month) && isset ($year)){
            foreach($karyawanALL as $karyawan){
                // Get ALL DATA gaji fakultas with condition
                $gajifakultas = Karyawan_Gaji_Fakultas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            foreach($gajifakultas as $key=> $gajifak){
                    // Get ALL DATA gaji universitas with condition
                $gajiuniv = Karyawan_Gaji_Universitas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                $potongan = Karyawan_Potongan::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                $pajak = Karyawan_Pajak::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();

            // Array Attribute
            $gajiunivarray = !empty($gajiuniv[$key]) ? $gajiuniv[$key]['total_gaji_univ'] : 0;
            $gajifakarray = !empty($gajifak) ? $gajifak['total_gaji_fakultas'] : 0;
            $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;

             // menampilkan data dalam bentuk array
             $laporanpajak[] = [
                'nama' => Karyawan::find($karyawan->id)->nama,
                'golongan' => Karyawan::find($karyawan->id)->golongan,
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

    return ResponseFormatter::success($laporanpajak, 'Data Laporan Pajak Karyawan Found');
    }
}
            public function laporanpotongan(Request $request){
            // Get ALL Karyawan attribute by month & year
            $karyawanALL = Karyawan::all();
            $month = $request->input('month');
            $year = $request->input('year');

            // Request by month & year
            if(isset($month) && isset ($year)){
                foreach($karyawanALL as $karyawan){
                    // Get ALL DATA potongan with condition
                    $potonganfh = Karyawan_Potongan::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                foreach($potonganfh as $key=> $potongan){
                        // Get ALL DATA PotonganTambahan with condition
                    $potongantambahan = Karyawan_Potongan_Tambahan::where('karyawan_potongan_id', $potongan->id)
                        ->whereMonth('created_at', $month)
                        ->whereYear('created_at', $year)
                        ->get();

                  // menampilkan data dalam bentuk array
                $laporanpotongan[] = [
                    'nama' =>   Karyawan::find($karyawan->id)->nama,
                    'golongan' => Karyawan::find($karyawan->id)->golongan,
                    'karyawan_potongan' => $potonganfh,
                    'karyawan_potongan_tambahan' => $potongantambahan,
                    ];
                }
                }
                 // Check if the laporanpotongan array is empty
                if (empty($laporanpotongan)) {
                    return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
                }
                return ResponseFormatter::success($laporanpotongan, 'Data Laporan Potongan Karyawan Found');
            }
}

            public function rekapitulasibank(Request $request){
            // Get ALL Karyawan attribute by month & year
            $karyawanALL = Karyawan::all();
            $month = $request->input('month');
            $year = $request->input('year');

              // Request by month & year
              if(isset($month) && isset ($year)){
                $rekapitulasibank = []; // Inisialisasi array untuk menampung data
                foreach($karyawanALL as $karyawan){
                    // Get ALL DATA
                    $gajiuniversitas = Karyawan_Gaji_Universitas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                    foreach($gajiuniversitas as $key=> $gajiuniv){
                        // Get ALL DATA
                    $gajifakultas = Karyawan_Gaji_Fakultas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                    $potongan = Karyawan_Potongan::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                    $pajak = Karyawan_Pajak::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();

                     // Array Attribute
            $gajifakarray = !empty($gajifakultas[$key]) ? $gajifakultas[$key]['total_gaji_fakultas'] : 0;
            $gajiunivarray = !empty($gajiuniv) ? $gajiuniv['total_gaji_univ'] : 0;
            $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;
            $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pendapatan_bersih'] : 0;
                 // menampilkan data dalam bentuk array
                 $data = [
                    'nama' => Karyawan::find($karyawan->id)->nama,
                    'golongan' => Karyawan::find($karyawan->id)->golongan,
                    'total_pendapatan' => $gajiunivarray + $gajifakarray,
                    'total_potongan' => $potonganarray,
                    'pendapatan_bersih' => $pajakarray,
                    'no_rekening' => Karyawan::find($karyawan->id)->norek_bank,
                    'nama_bank' => Karyawan::find($karyawan->id)->nama_bank
                    ];

                // Memilah data berdasarkan nama_bank
                if ( Karyawan::find($karyawan->id)->nama_bank === 'Mandiri') {
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
        return ResponseFormatter::success($rekapitulasibank, 'Data Laporan Rekapitulasi Bank Karyawan Found');
        }

    }
}
