<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Models\dosenlb\Doslb_Pajak;
use App\Http\Controllers\Controller;
use App\Models\dosentetap\Dostap_Pajak;
use App\Models\karyawan\Karyawan_Pajak;

class TransaksiGajiController extends Controller
{
    // Get Data Bulan dan Tahun Transaksi Gaji Dosen Tetap
    public function getDataByMonthAndYearDostap()
    {
        $pajak = Dostap_Pajak::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
        ->groupByRaw('YEAR(created_at), MONTH(created_at)')
        ->get();

        return ResponseFormatter::success($pajak);
    }
     // Get Data Bulan dan Tahun Transaksi Gaji Karyawan
     public function getDataByMonthAndYearKaryawan()
     {
         $pajak = Karyawan_Pajak::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
         ->groupByRaw('YEAR(created_at), MONTH(created_at)')
         ->get();

         return ResponseFormatter::success($pajak);
     }
    // Get Data Bulan dan Tahun Transaksi Gaji Dosen Luar Biasa
     public function getDataByMonthAndYearDoslb()
     {
         $pajak = Doslb_Pajak::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
         ->groupByRaw('YEAR(created_at), MONTH(created_at)')
         ->get();

         return ResponseFormatter::success($pajak);
     }

    //  Hapus Transaksi Gaji Dosen Tetap melalui relasi dari Pajak
     public function destroygajidostap($id){
    try{
        // Get Data Pajak
        $pajak = Dostap_Pajak::find($id);
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
            // Delete related Dostap_Potongan records
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

  //  Hapus Transaksi Gaji Karyawan melalui relasi dari Pajak
  public function destroygajikaryawan($id){
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


  //  Hapus Transaksi Gaji Dosen Luar Biasa melalui relasi dari Pajak
  public function destroygajidoslb($id){
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
