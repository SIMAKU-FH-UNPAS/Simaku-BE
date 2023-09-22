<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\dosenlb\Doslb_Pajak;
use App\Http\Controllers\Controller;
use App\Models\dosenlb\Doslb_Potongan;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use App\Models\dosenlb\Doslb_Potongan_Tambahan;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan_Tambahan;

class TransaksiGajiController extends Controller
{
    // Get Data Bulan dan Tahun Transaksi Gaji Dosen Luar Biasa
    public function getDataByMonthAndYear()
    {
        $komponenpendapatan = Doslb_Komponen_Pendapatan::selectRaw('MONTH(created_at) as bulan, YEAR(created_at) as tahun')
        ->groupByRaw('YEAR(created_at), MONTH(created_at)')
        ->get();

        // $pajak->transform(function ($item, $key) {
        //     $item->bulan = date("F", mktime(0, 0, 0, $item->bulan, 1)); // Mengubah angka bulan menjadi nama bulan
        //     return $item;
        // });

        return ResponseFormatter::success($komponenpendapatan);
    }

    // Get Data Gaji Univ, Gaji Fak, Honor Fak, Potongan, Potongan Tambahan, Pajak Dosen Luar Biasa by periode
    public function GetTransaksiGajibyPeriode(Request $request, $id){
        // Get ALL Dosen LB attribute by month & year
         $dosenluarbiasa = Dosen_Luar_Biasa::find($id);
         $month = $request->input('month');
         $year = $request->input('year');

         $transaksigaji = []; // Inisialisasi array kosong
         // Request by month & year
         if(isset($month) && isset ($year)){
                 // Get ALL DATA komponen pendapatan with condition
                 $komponenpendapatan = Doslb_Komponen_Pendapatan::where('dosen_luar_biasa_id', $dosenluarbiasa->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                 $komponenpendapatantambahan = []; // Inisialisasi array kosong sebelum loop
                 foreach($komponenpendapatan as $key=> $kompen){
                     // Get ALL DATA komponen pendapatan tambahan with condition
                 $komponenpendapatantambahan = Doslb_Komponen_Pendapatan_Tambahan::where('doslb_pendapatan_id', $kompen->id)
                     ->whereMonth('created_at', $month)
                     ->whereYear('created_at', $year)
                     ->get();

                    // Get ALL DATA potongan with condition
                 $potongan = Doslb_Potongan::where('dosen_luar_biasa_id', $dosenluarbiasa->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
                 $potongantambahan = []; // Inisialisasi array kosong sebelum loop
                 foreach($potongan as $key=> $pot){
                // Get ALL DATA potongan tambahan with condition
                $potongantambahan = Doslb_Potongan_Tambahan::where('doslb_potongan_id', $pot->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get();
                 }
                // Get ALL DATA pajak with condition
                $pajak = Doslb_Pajak::where('dosen_luar_biasa_id', $dosenluarbiasa->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();

                if ($komponenpendapatan) {
                    $periode = [
                        'month' => $komponenpendapatan->first()->created_at->format('F'), // Nama bulan (contoh: Januari, Februari)
                        'year' => $komponenpendapatan->first()->created_at->format('Y'), // Tahun (contoh: 2023)
                    ];
                }

             // menampilkan data dalam bentuk array
                 $transaksigaji[] = [
                    'dosen_luar_biasa_id' => $dosenluarbiasa->id,
                     'no_pegawai' => $dosenluarbiasa->no_pegawai,
                     'nama' => $dosenluarbiasa->nama,
                     'golongan' => $dosenluarbiasa->golongan,
                     'jabatan' => $dosenluarbiasa->jabatan,
                     'nama_bank' => $dosenluarbiasa->nama_bank,
                     'periode' => $periode,
                     'dosenlb_komponen_pendapatan' => $komponenpendapatan,
                     'dosenlb_komponen_pendapatan_tambahan' => $komponenpendapatantambahan,
                     'dosenlb_potongan' => $potongan,
                     'dosenlb_potongan_tambahan' => $potongantambahan,
                     'dosenlb_pajak' => $pajak

                 ];
                }
            }

             // Check if the transaksi gaji array is empty
         if (empty($transaksigaji)) {
             return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
         }

         return ResponseFormatter::success($transaksigaji, 'Data  Transaksi Gaji Dosen Luar Biasa Found');
     }


    // Get Data Gaji Univ, Gaji Fak, Honor Fak, Potongan, Potongan Tambahan, Pajak Dosen Luar Biasa
    public function GetALLTransaksiGaji($id){
       // Get Dosen LB
    $dosenluarbiasa = Dosen_Luar_Biasa::find($id);
     // Inisialisasi data dosen luar biasa
     $transaksigaji[] = [
        'dosen_luar_biasa_id' => $dosenluarbiasa->id,
        'no_pegawai' => $dosenluarbiasa->no_pegawai,
        'nama' => $dosenluarbiasa->nama,
        'golongan' => $dosenluarbiasa->golongan,
        'jabatan' => $dosenluarbiasa->jabatan,
        'nama_bank' => $dosenluarbiasa->nama_bank,
    ];
     // Periksa apakah data dosen luar biasa ditemukan
     if (!$dosenluarbiasa) {
        return ResponseFormatter::error(null, 'Data Dosen Luar Biasa Not Found', 404);
    }

    // Get ALL DATA komponen pendapatan
    $komponenpendapatanALL = Doslb_Komponen_Pendapatan::where('dosen_luar_biasa_id', $dosenluarbiasa->id)->get();

    foreach ($komponenpendapatanALL as $kompen) {
        // Get data created_at dari komponen pendapatan saat ini
        $periode = [
            'month' => $kompen->created_at->format('m'), // Bulan (misal: 01, 02, dst.)
            'year' => $kompen->created_at->format('Y'),  // Tahun (misal: 2023)
        ];


         // Get ALL DATA komponen pendapatan  with condition
         $komponenpendapatan = Doslb_Komponen_Pendapatan::where('dosen_luar_biasa_id', $dosenluarbiasa->id)
         ->whereMonth('created_at', $periode['month'])
         ->whereYear('created_at', $periode['year'])
         ->get();

         $komponenpendapatantambahan = []; // Inisialisasi array kosong sebelum loop
         foreach ($komponenpendapatan as $kp){
        // Get ALL DATA komponen pendapatan tambahan with condition
        $komponenpendapatantambahan = Doslb_Komponen_Pendapatan_Tambahan::where('doslb_pendapatan_id', $kp->id)
            ->whereMonth('created_at', $periode['month'])
            ->whereYear('created_at', $periode['year'])
            ->get();
         }
        // Get ALL DATA potongan with condition
        $potongan = Doslb_Potongan::where('dosen_luar_biasa_id', $dosenluarbiasa->id)
            ->whereMonth('created_at', $periode['month'])
            ->whereYear('created_at', $periode['year'])
            ->get();

        $potongantambahan = []; // Inisialisasi array kosong sebelum loop
        // Get ALL DATA potongan tambahan with condition
        foreach ($potongan as $pot) {
            $potongantambahan = Doslb_Potongan_Tambahan::where('doslb_potongan_id', $pot->id)
                ->whereMonth('created_at', $periode['month'])
                ->whereYear('created_at', $periode['year'])
                ->get();
        }

        // Get ALL DATA pajak with condition
        $pajak = Doslb_Pajak::where('dosen_luar_biasa_id', $dosenluarbiasa->id)
            ->whereMonth('created_at', $periode['month'])
            ->whereYear('created_at', $periode['year'])
            ->get();

        // menampilkan data dalam bentuk array
        $transaksigaji[] = [
           'periode' => [
                        'month' => $kompen->created_at->format('F'), // Nama bulan (contoh: Januari, Februari)
                        'year' => $kompen->created_at->format('Y'),  // Tahun (contoh: 2023)
                    ],
            'dosenlb_komponen_pendapatan' => $komponenpendapatan,
            'dosenlb_komponen_pendapatan_tambahan' => $komponenpendapatantambahan,
            'dosenlb_potongan' => $potongan,
            'dosenlb_potongan_tambahan' => $potongantambahan,
            'dosenlb_pajak' => $pajak,
        ];
    }

    // Check if the transaksi gaji array is empty
    if (empty($transaksigaji)) {
        return ResponseFormatter::error(null, 'Data Transaksi Gaji Dosen Luar Biasa Not Found', 404);
    }

    return ResponseFormatter::success($transaksigaji, 'Data Transaksi Gaji Dosen Luar Biasa Found');
}


     //  Hapus Transaksi Gaji Dosen Luar Biasa melalui relasi dari Pajak
  public function destroygaji($id){
    try{
        // Get Data Pajak
        $pajak = Doslb_Pajak::find($id);
         // Check if Data Pajak exists
         if(!$pajak){
            throw new Exception('Data Pajak not found');
        }
        //Delete the related records with Pajak
        $pajak->komponen_pendapatan()->each(function ($pendapatan){
            // Delete related Doslb_Komponen_Pendapatan_Tambahan records
            $pendapatan->komponenpendapatantambahan()->delete();
            $pendapatan->delete();
        });
        $pajak->potongan()->each(function ($potongan) {
            // Delete related Potongan records
            $potongan->potongantambahan()->delete();
            $potongan->delete();
        });
         // Delete Data Pajak
         $pajak->delete();


        return ResponseFormatter::success('Data Transaksi Gaji deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
     }
}
