<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\karyawan\Karyawan;
use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use App\Models\karyawan\Karyawan_Gaji_Universitas;
use App\Models\karyawan\Karyawan_Honor_Fakultas;
use App\Models\karyawan\Karyawan_Pajak;
use App\Models\karyawan\Karyawan_Potongan;
use App\Models\karyawan\Karyawan_Potongan_Tambahan;

class TransaksiGajiController extends Controller
{
    // Get Data Bulan dan Tahun Transaksi Gaji Karyawan
    public function getDataByMonthAndYear()
    {
        $gaji = Karyawan_Gaji_Universitas::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
        ->groupByRaw('YEAR(created_at), MONTH(created_at)')
        ->get();

          // $gaji->transform(function ($item, $key) {
        //     $item->bulan = date("F", mktime(0, 0, 0, $item->bulan, 1)); // Mengubah angka bulan menjadi nama bulan
        //     return $item;
        // });

        return ResponseFormatter::success($gaji);
    }

     // Get Data Gaji Univ, Gaji Fak, Honor Fak, Potongan, Potongan Tambahan, Pajak Karyawan by periode
     public function GetTransaksiGajibyPeriode(Request $request, $id){
        // Get ALL Karyawan attribute by month & year
         $karyawan = Karyawan::find($id);
         $month = $request->input('month');
         $year = $request->input('year');

         // Request by month & year
         if(isset($month) && isset ($year)){
                 // Get ALL DATA gaji univ and gaji fak with condition
                 $gajiuniversitas = Karyawan_Gaji_Universitas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
                 $gajifakultas = Karyawan_Gaji_Fakultas::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
             foreach($gajifakultas as $key=> $gajifak){
                     // Get ALL DATA honor fakultas tambahan with condition
                 $honorfakultas = Karyawan_Honor_Fakultas::where('karyawan_gaji_fakultas_id', $gajifak->id)
                     ->whereMonth('created_at', $month)
                     ->whereYear('created_at', $year)
                     ->get();
                    // Get ALL DATA potongan with condition
                 $potongan = Karyawan_Potongan::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();
            foreach($potongan as $key=> $pot){
                // Get ALL DATA potongan tambahan with condition
                $potongantambahan = Karyawan_Potongan_Tambahan::where('karyawan_potongan_id', $pot->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get();

                // Get ALL DATA pajak with condition
                $pajak = Karyawan_Pajak::where('karyawan_id', $karyawan->id)->whereMonth('created_at', $month)->whereYear('created_at',$year)->get();

                if ($gajiuniversitas) {
                    $periode = [
                        'month' => $gajiuniversitas->first()->created_at->format('F'), // Nama bulan (contoh: Januari, Februari)
                        'year' => $gajiuniversitas->first()->created_at->format('Y'), // Tahun (contoh: 2023)
                    ];
                }

             // menampilkan data dalam bentuk array
                 $transaksigaji[] = [
                    'karyawan_id' => $karyawan->id,
                     'no_pegawai' => $karyawan->no_pegawai,
                     'nama' => $karyawan->nama,
                     'golongan' => $karyawan->golongan,
                     'jabatan' => $karyawan->jabatan,
                     'nama_bank' => $karyawan->nama_bank,
                     'periode' => $periode,
                     'karyawan_gaji_univ' => $gajiuniversitas,
                     'karyawan_gaji_fakultas' => $gajifakultas,
                     'karyawan_honor_fakultas' => $honorfakultas,
                     'karyawan_potongan' => $potongan,
                     'karyawan_potongan_tambahan' => $potongantambahan,
                     'karyawan_pajak' => $pajak

                 ];
                }
             }
             }

             // Check if the transaksi gaji array is empty
         if (empty($transaksigaji)) {
             return ResponseFormatter::error(null, 'No data found for the specified month and year', 404);
         }

         return ResponseFormatter::success($transaksigaji, 'Data  Transaksi Gaji Karyawan Found');
     }


    // Get Data Gaji Univ, Gaji Fak, Honor Fak, Potongan, Potongan Tambahan, Pajak Karyawan
    public function GetALLTransaksiGaji($id){
       // Get Dosen LB
    $karyawan = Karyawan::find($id);

    // Get ALL DATA Gaji Universitas
    $gajiuniversitasALL = Karyawan_Gaji_Universitas::where('karyawan_id', $karyawan->id)->get();

    foreach ($gajiuniversitasALL as $gajiuniv) {
        // Get data created_at dari Gaji Universitas saat ini
        $periode = [
            'month' => $gajiuniv->created_at->format('m'), // Bulan (misal: 01, 02, dst.)
            'year' => $gajiuniv->created_at->format('Y'),  // Tahun (misal: 2023)
        ];

          // Get ALL DATA gaji universitas  with condition
          $gajiuniversitas = Karyawan_Gaji_Universitas::where('karyawan_id', $karyawan->id)
          ->whereMonth('created_at', $periode['month'])
          ->whereYear('created_at', $periode['year'])
          ->get();

         // Get ALL DATA gaji fakultas  with condition
         $gajifakultas = Karyawan_Gaji_Fakultas::where('karyawan_id', $karyawan->id)
         ->whereMonth('created_at', $periode['month'])
         ->whereYear('created_at', $periode['year'])
         ->get();

         foreach ($gajifakultas as $gajifak){
        // Get ALL DATA honor fakultas tambahan with condition
        $honorfakultas = Karyawan_Honor_Fakultas::where('karyawan_gaji_fakultas_id', $gajifak->id)
            ->whereMonth('created_at', $periode['month'])
            ->whereYear('created_at', $periode['year'])
            ->get();
         }
        // Get ALL DATA potongan with condition
        $potongan = Karyawan_Potongan::where('karyawan_id', $karyawan->id)
            ->whereMonth('created_at', $periode['month'])
            ->whereYear('created_at', $periode['year'])
            ->get();

        // Get ALL DATA potongan tambahan with condition
        foreach ($potongan as $pot) {
            $potongantambahan = Karyawan_Potongan_Tambahan::where('karyawan_potongan_id', $pot->id)
                ->whereMonth('created_at', $periode['month'])
                ->whereYear('created_at', $periode['year'])
                ->get();
        }

        // Get ALL DATA pajak with condition
        $pajak = Karyawan_Pajak::where('karyawan_id', $karyawan->id)
            ->whereMonth('created_at', $periode['month'])
            ->whereYear('created_at', $periode['year'])
            ->get();

        // menampilkan data dalam bentuk array
        $transaksigaji[] = [
            'karyawan_id' => $karyawan->id,
            'no_pegawai' => $karyawan->no_pegawai,
            'nama' => $karyawan->nama,
            'golongan' => $karyawan->golongan,
            'jabatan' => $karyawan->jabatan,
            'nama_bank' => $karyawan->nama_bank,
           'periode' => [
                        'month' => $gajiuniv->created_at->format('F'), // Nama bulan (contoh: Januari, Februari)
                        'year' => $gajiuniv->created_at->format('Y'),  // Tahun (contoh: 2023)
                    ],
            'karyawan_gaji_univ' => $gajiuniversitas,
            'karyawan_gaji_fakultas' => $gajifakultas,
            'karyawan_honor_fakultas' => $honorfakultas,
            'karyawan_potongan' => $potongan,
            'karyawan_potongan_tambahan' => $potongantambahan,
            'karyawan_pajak' => $pajak
        ];
    }

    // Check if the transaksi gaji array is empty
    if (empty($transaksigaji)) {
        return ResponseFormatter::error(null, 'Data Transaksi Gaji Karyawan Not Found', 404);
    }

    return ResponseFormatter::success($transaksigaji, 'Data Transaksi Gaji Karyawan Found');
}
     //  Hapus Transaksi Gaji Karyawan melalui relasi dari Pajak
  public function destroygaji($id){
    try{
        // Get Data Pajak
        $pajak = Karyawan_Pajak::find($id);
         // Check if Data Pajak exists
         if(!$pajak){
            throw new Exception('Data Pajak not found');
        }
        //Delete the related records with Pajak
        $pajak->gaji_universitas()->delete();
        $pajak->gaji_fakultas()->each(function ($gajiFakultas) {
            // Delete related Dostap_Honor_Fakultas records
            $gajiFakultas->honorfakultastambahan()->delete();
            $gajiFakultas->delete();
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
