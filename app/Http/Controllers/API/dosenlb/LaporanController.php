<?php

namespace App\Http\Controllers\API\dosenlb;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan_Tambahan;
use App\Models\dosenlb\Doslb_Pajak;
use App\Models\dosenlb\Doslb_Potongan;
use App\Models\dosenlb\Doslb_Potongan_Tambahan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function rekapitulasipendapatan(Request $request){
        // Get ALL Dosen LB attribute by month & year
         $dosenlbALL = Dosen_Luar_Biasa::all();
         $month = $request->input('month');
         $year = $request->input('year');

         // Request by month & year
         if(isset($month) && isset ($year)){
             foreach($dosenlbALL as $dosenlb){
                 // Get ALL DATA komponen pendapatan with condition
                 $komponenpendapatan = Doslb_Komponen_Pendapatan::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
             foreach($komponenpendapatan as $key=> $kompen){
                     // Get ALL DATA komponen pendapatan tambahan with condition
                 $komponenpendapatantambahan = Doslb_Komponen_Pendapatan_Tambahan::where('doslb_pendapatan_id', $kompen->id)
                     ->whereMonth('created_at', $month)
                     ->whereYear('created_at', $year)
                     ->get();

             // Array Attribute
             $komponenpendapatanarray = !empty($kompen) ? $kompen['total_komponen_pendapatan'] : 0;

             // menampilkan data dalam bentuk array
                 $rekapitulasipendapatan[] = [
                     'nama' => Dosen_Luar_Biasa::find($dosenlb->id)->nama,
                     'golongan' => Dosen_Luar_Biasa::find($dosenlb->id)->golongan,
                     'dosenlb_komponen_pendapatan' => $komponenpendapatan,
                     'dosenlb_komponen_pendapatan_tambahan' => $komponenpendapatantambahan,
                     'total_pendapatan' => $komponenpendapatanarray

                 ];
             }
             }
             // Check if the rekapitulasipendapatan array is empty
         if (empty($rekapitulasipendapatan)) {
             return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
         }

         return ResponseFormatter::success($rekapitulasipendapatan, 'Data Laporan Rekapitulasi Pendapatan Dosen Luar Biasa Found');
     }
     }

         public function pendapatanbersih(Request $request){
              // Get ALL Dosen LB attribute by month & year
         $dosenlbALL = Dosen_Luar_Biasa::all();
         $month = $request->input('month');
         $year = $request->input('year');


             // Request by month & year
             if(isset($month) && isset ($year)){
                 foreach($dosenlbALL as $dosenlb){
                     // Get ALL DATA komponen pendapatan with condition
                 $komponenpendapatan = Doslb_Komponen_Pendapatan::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                 foreach($komponenpendapatan as $key=> $kompen){
                         // Get ALL DATA with condition
                     $potongan = Doslb_Potongan::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                     $pajak = Doslb_Pajak::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();


              // Array Attribute
              $komponenpendapatanarray = !empty($kompen) ? $kompen['total_komponen_pendapatan'] : 0;
             $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;
             $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pendapatan_bersih'] : 0;

              // menampilkan data dalam bentuk array
              $pendapatanbersih[] = [
                 'nama' => Dosen_Luar_Biasa::find($dosenlb->id)->nama,
                 'golongan' => Dosen_Luar_Biasa::find($dosenlb->id)->golongan,
                 'total_komponen_pendapatan' => $komponenpendapatanarray,
                 'total_potongan' => $potonganarray,
                 'pendapatan_bersih' => $pajakarray

             ];
         }
     }
         // Check if the pendapatanbersih array is empty
         if (empty($pendapatanbersih)) {
             return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
         }

         return ResponseFormatter::success($pendapatanbersih, 'Data Laporan Pendapatan Bersih Dosen Luar Biasa Found');
 }
 }

     public function laporanpajak(Request $request){
         // Get ALL Dosen LB attribute by month & year
         $dosenlbALL = Dosen_Luar_Biasa::all();
         $month = $request->input('month');
         $year = $request->input('year');

         // Request by month & year
         if(isset($month) && isset ($year)){
            foreach($dosenlbALL as $dosenlb){
                // Get ALL DATA komponen pendapatan with condition
            $komponenpendapatan = Doslb_Komponen_Pendapatan::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
            foreach($komponenpendapatan as $key=> $kompen){
                     // Get ALL DATA with condition
                     $potongan = Doslb_Potongan::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                     $pajak = Doslb_Pajak::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();

             // Array Attribute
             $komponenpendapatanarray = !empty($kompen) ? $kompen['total_komponen_pendapatan'] : 0;
             $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;

              // menampilkan data dalam bentuk array
              $laporanpajak[] = [
                 'nama' => Dosen_Luar_Biasa::find($dosenlb->id)->nama,
                 'golongan' => Dosen_Luar_Biasa::find($dosenlb->id)->golongan,
                 'total_komponen_pendapatan' => $komponenpendapatanarray,
                 'total_potongan' => $potonganarray,
                 'dosenlb_pajak' => $pajak

                 ];
         }
     }
     // Check if the laporanpajak array is empty
     if (empty($laporanpajak)) {
         return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
     }

     return ResponseFormatter::success($laporanpajak, 'Data Laporan Pajak Dosen Luar Biasa Found');
     }
 }
             public function laporanpotongan(Request $request){
             // Get ALL Dosen LB attribute by month & year
            $dosenlbALL = Dosen_Luar_Biasa::all();
            $month = $request->input('month');
            $year = $request->input('year');

             // Request by month & year
             if(isset($month) && isset ($year)){
                 foreach($dosenlbALL as $dosenlb){
                     // Get ALL DATA potongan with condition
                     $potonganfh = Doslb_Potongan::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                 foreach($potonganfh as $key=> $potongan){
                         // Get ALL DATA PotonganTambahan with condition
                     $potongantambahan = Doslb_Potongan_Tambahan::where('doslb_potongan_id', $potongan->id)
                         ->whereMonth('created_at', $month)
                         ->whereYear('created_at', $year)
                         ->get();

                   // menampilkan data dalam bentuk array
                 $laporanpotongan[] = [
                     'nama' =>   Dosen_Luar_Biasa::find($dosenlb->id)->nama,
                     'golongan' => Dosen_Luar_Biasa::find($dosenlb->id)->golongan,
                     'dosenlb_potongan' => $potonganfh,
                     'dosenlb_potongan_tambahan' => $potongantambahan,
                     ];
                 }
                 }
                  // Check if the laporanpotongan array is empty
                 if (empty($laporanpotongan)) {
                     return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
                 }
                 return ResponseFormatter::success($laporanpotongan, 'Data Laporan Potongan Dosen Luar Biasa Found');
             }
 }

             public function rekapitulasibank(Request $request){
              // Get ALL Dosen LB attribute by month & year
              $dosenlbALL = Dosen_Luar_Biasa::all();
              $month = $request->input('month');
              $year = $request->input('year');

               // Request by month & year
               if(isset($month) && isset ($year)){
                 $rekapitulasibank = []; // Inisialisasi array untuk menampung data
                 foreach($dosenlbALL as $dosenlb){
                     // Get ALL DATA
                     $komponenpendapatan = Doslb_Komponen_Pendapatan::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                     foreach($komponenpendapatan as $key=> $kompen){
                             // Get ALL DATA with condition
                         $potongan = Doslb_Potongan::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                         $pajak = Doslb_Pajak::where('dosen_luar_biasa_id', $dosenlb->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();


                      // Array Attribute
                    $komponenpendapatanarray = !empty($kompen) ? $kompen['total_komponen_pendapatan'] : 0;
                    $potonganarray = !empty($potongan[$key]) ? $potongan[$key]['total_potongan'] : 0;
                    $pajakarray = !empty($pajak[$key]) ? $pajak[$key]['pendapatan_bersih'] : 0;
                  // menampilkan data dalam bentuk array
                  $data = [
                     'nama' => Dosen_Luar_Biasa::find($dosenlb->id)->nama,
                     'golongan' => Dosen_Luar_Biasa::find($dosenlb->id)->golongan,
                     'total_pendapatan' => $komponenpendapatanarray,
                     'total_potongan' => $potonganarray,
                     'pendapatan_bersih' => $pajakarray,
                     'no_rekening' => Dosen_Luar_Biasa::find($dosenlb->id)->norek_bank,
                     'nama_bank' => Dosen_Luar_Biasa::find($dosenlb->id)->nama_bank
                     ];

                 // Memilah data berdasarkan nama_bank
                 if (Dosen_Luar_Biasa::find($dosenlb->id)->nama_bank === 'Mandiri') {
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
         return ResponseFormatter::success($rekapitulasibank, 'Data Laporan Rekapitulasi Bank Dosen Luar Biasa Found');
         }

     }
 }
