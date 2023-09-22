<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\dosenlb\Doslb_Pajak;
use App\Models\dosentetap\Dostap_Pajak;
use App\Models\karyawan\Karyawan_Pajak;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

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
}
