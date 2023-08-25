<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\dosenlb\Doslb_Pajak;
use App\Http\Controllers\Controller;

class TransaksiGajiController extends Controller
{
    // Get Data Bulan dan Tahun Transaksi Gaji Dosen Luar Biasa
    public function getDataByMonthAndYear()
    {
        $pajak = Doslb_Pajak::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
        ->groupByRaw('YEAR(created_at), MONTH(created_at)')
        ->get();

        return ResponseFormatter::success($pajak);
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
