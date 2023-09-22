<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use Illuminate\Http\Request;
use App\Models\karyawan\Karyawan_Gaji_Universitas;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\karyawan\CreateGajiUnivRequest;
use App\Http\Requests\karyawan\UpdateGajiUnivRequest;
use App\Http\Controllers\API\karyawan\HitungPajakController;


class GajiUnivController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $karyawan_id= $request->input('karyawan_id');
        $gaji_pokok = $request->input('gaji_pokok');
        $tj_struktural = $request->input('tj_struktural');
        $tj_pres_kerja = $request->input('tj_pres_kerja');
        $u_lembur_hk = $request->input('u_lembur_hk');
        $u_lembur_hl= $request->input('u_lembur_hl');
        $trans_kehadiran= $request->input('trans_kehadiran');
        $tj_fungsional= $request->input('tj_fungsional');
        $tj_khs_istimewa= $request->input('tj_khs_istimewa');
        $tj_tambahan= $request->input('tj_tambahan');
        $honor_univ= $request->input('honor_univ');
        $tj_suami_istri= $request->input('tj_suami_istri');
        $tj_anak= $request->input('tj_anak');
        $total_gaji_univ= $request->input('total_gaji_univ');
        $month = $request->input('month');
        $year = $request->input('year');
        $limit = $request->input('limit', 10);

        $gajiunivQuery = Karyawan_Gaji_Universitas::query();

           // Get single data
    if($id)
    {
        $gajiuniv= $gajiunivQuery->find($id);

        if($gajiuniv){
            return ResponseFormatter::success($gajiuniv, 'Data Gaji Universitas Karyawan found');
        }
            return ResponseFormatter::error('Data Gaji Universitas Karyawan not found', 404);
    }

    //    Get multiple Data
    $gajiuniv = $gajiunivQuery;

    // // Get by attribute
    if($gaji_pokok)
    {
        $gajiuniv->where('gaji_pokok', 'like', '%'.$gaji_pokok.'%');
    }
    if($tj_struktural)
    {
        $gajiuniv->where('tj_struktural', 'like', '%'.$tj_struktural.'%');

    }
    if($tj_pres_kerja)
    {
        $gajiuniv->where('tj_pres_kerja', 'like', '%'.$tj_pres_kerja.'%');

    }
    if($u_lembur_hk)
    {
        $gajiuniv->where('u_lembur_hk', 'like', '%'.$u_lembur_hk.'%');

    }
    if($u_lembur_hl)
    {
        $gajiuniv->where('u_lembur_hl', 'like', '%'.$u_lembur_hl.'%');

    }
    if($trans_kehadiran)
    {
        $gajiuniv->where('trans_kehadiran', 'like', '%'.$trans_kehadiran.'%');

    }
    if($tj_fungsional)
    {
        $gajiuniv->where('tj_fungsional', 'like', '%'.$tj_fungsional.'%');

    }
    if($tj_khs_istimewa)
    {
        $gajiuniv->where('tj_khs_istimewa', 'like', '%'.$tj_khs_istimewa.'%');

    }
    if($tj_tambahan)
    {
        $gajiuniv->where('tj_tambahan', 'like', '%'.$tj_tambahan.'%');

    }
    if($honor_univ)
    {
        $gajiuniv->where('honor_univ', 'like', '%'.$honor_univ.'%');

    }
    if($tj_suami_istri)
    {
        $gajiuniv->where('tj_suami_istri', 'like', '%'.$tj_suami_istri.'%');

    }
    if($tj_anak)
    {
        $gajiuniv->where('tj_anak', 'like', '%'.$tj_anak.'%');

    }
    if($total_gaji_univ)
    {
        $gajiuniv->where('total_gaji_univ', 'like', '%'.$total_gaji_univ.'%');

    }
    if($month && $year){
        $gajiuniv->whereMonth('created_at', $month)
        ->whereYear('created_at', $year);
    }
    if ($karyawan_id) {
        $gajiuniv->where('karyawan_id', $karyawan_id);
    }



    return ResponseFormatter::success(
        $gajiuniv->paginate($limit),
        'Data Gaji Universitas Karyawan Found'
    );
    }



    public function create(CreateGajiUnivRequest $request){
        try{
            $karyawan_id = $request->karyawan_id;

            // Get the current month and year
            $currentMonth = now()->month;
            $currentYear = now()->year;


        // Check if data already exists for the current month and year
        $existingGajiUniv = Karyawan_Gaji_Universitas::where('karyawan_id', $karyawan_id)
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->first();

        if ($existingGajiUniv) {
            return ResponseFormatter::error('Data Gaji Universitas Karyawan for this month already exists', 400);
        }

            $gajiuniversitas = new Karyawan_Gaji_Universitas;
            $total_gaji_univ = $gajiuniversitas->total_gaji_univ($request);
            // Create Gaji Universitas
            $gajiuniv = Karyawan_Gaji_Universitas::create([
                'karyawan_id' => $request-> karyawan_id,
                'gaji_pokok' => $request-> gaji_pokok,
                'tj_struktural' => $request-> tj_struktural,
                'tj_pres_kerja' => $request-> tj_pres_kerja,
                'u_lembur_hk' => $request-> u_lembur_hk,
                'u_lembur_hl' => $request-> u_lembur_hl,
                'trans_kehadiran' => $request-> trans_kehadiran,
                'tj_fungsional' => $request-> tj_fungsional,
                'tj_khs_istimewa' => $request-> tj_khs_istimewa,
                'tj_tambahan' => $request-> tj_tambahan,
                'honor_univ' => $request-> honor_univ,
                'tj_suami_istri' => $request-> tj_suami_istri,
                'tj_anak' => $request-> tj_anak,
                'total_gaji_univ' => $total_gaji_univ,
            ]);
            if(!$gajiuniv){
                throw new Exception('Data Gaji Universitas Karyawan not created');
            }
            return ResponseFormatter::success($gajiuniv, 'Data Gaji Universitas Karyawan created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

        public function update(UpdateGajiUnivRequest $request, $id)
        {
            try {

                // Get Gaji Universitas
                $gajiuniv = Karyawan_Gaji_Universitas::find($id);

                // Check if Gaji Universitas exists
                if(!$gajiuniv){
                    throw new Exception('Data Gaji Universitas Karyawan not found');
                }

                // Update Gaji Universitas
                $gajiuniversitas = new Karyawan_Gaji_Universitas;
                $total_gaji_univ = $gajiuniversitas->total_gaji_univ($request);
                $gajiuniv -> update([
                    'karyawan_id' => $request-> karyawan_id,
                    'gaji_pokok' => $request-> gaji_pokok,
                    'tj_struktural' => $request-> tj_struktural,
                    'tj_pres_kerja' => $request-> tj_pres_kerja,
                    'u_lembur_hk' => $request-> u_lembur_hk,
                    'u_lembur_hl' => $request-> u_lembur_hl,
                    'trans_kehadiran' => $request-> trans_kehadiran,
                    'tj_fungsional' => $request-> tj_fungsional,
                    'tj_khs_istimewa' => $request-> tj_khs_istimewa,
                    'tj_tambahan' => $request-> tj_tambahan,
                    'honor_univ' => $request-> honor_univ,
                    'tj_suami_istri' => $request-> tj_suami_istri,
                    'tj_anak' => $request-> tj_anak,
                    'total_gaji_univ' => $total_gaji_univ,
            ]);
            // Memanggil Controller Hitung Pajak untuk update value rumus pajak
            $hitungPajakController = new HitungPajakController();
            $hitungPajakController->Hitung_Pajak_Univ($request, $gajiuniv->id); // Memanggil method Hitung_Pajak


        return ResponseFormatter::success($gajiuniv, 'Data Gaji Universitas Karyawan updated');
    }
    catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
 }

    public function destroy($id){
        try{
            // Get Data Gaji Universitas
            $gajiuniv = Karyawan_Gaji_Universitas::find($id);

            // Check if Data Gaji Universitas exists
            if(!$gajiuniv){
                throw new Exception('Data Gaji Universitas Karyawan not found');
            }

            // Delete Data Gaji Universitas
            $gajiuniv->delete();

            return ResponseFormatter::success('Data Gaji Universitas Karyawan deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    }
