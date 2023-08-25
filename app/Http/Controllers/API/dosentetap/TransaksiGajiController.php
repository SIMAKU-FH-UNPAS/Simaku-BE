<?php

namespace App\Http\Controllers\API\dosentetap;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\dosentetap\Dostap_Pajak;

class TransaksiGajiController extends Controller
{
    // Get Data Bulan dan Tahun Transaksi Gaji Dosen Tetap
    public function getDataByMonthAndYear()
    {
        $pajak = Dostap_Pajak::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year')
        ->groupByRaw('YEAR(created_at), MONTH(created_at)')
        ->get();

        return ResponseFormatter::success($pajak);
    }
     //  Hapus Transaksi Gaji Dosen Tetap melalui relasi dari Pajak
     public function destroygaji($id){
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

}
