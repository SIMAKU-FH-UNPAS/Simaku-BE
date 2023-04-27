<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Models\Gaji_Universitas;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGajiUnivRequest;
use App\Http\Requests\UpdateGajiUnivRequest;
use Illuminate\Support\Facades\DB;

class GajiUnivController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $gaji_pokok = $request->input('gaji_pokok');
        $tj_struktural = $request->input('tj_struktural');
        $tj_pres_kerja = $request->input('tj_pres_kerja');
        $u_lembur_hk = $request->input('u_lembur_hk');
        $u_lembur_hl= $request->input('u_lembur_hl');
        $trans_kehadiran= $request->input('trans_kehadiran');
        $tj_fungsional= $request->input('tj_fungsional');
        $gaji_pusat= $request->input('gaji_pusat');
        $tj_khs_istimewa= $request->input('tj_khs_istimewa');
        $tj_tambahan= $request->input('tj_tambahan');
        $honor_univ= $request->input('honor_univ');
        $tj_suami_istri= $request->input('tj_suami_istri');
        $tj_anak= $request->input('tj_anak');
        $total_gaji_univ= $request->input('total_gaji_univ');
        $pegawai_id= $request->input('pegawai_id');
        $limit = $request->input('limit', 10);

        $gajiunivQuery = Gaji_Universitas::query();

           // Get single data
    if($id)
    {
        $gajiuniv= $gajiunivQuery->find($id);

        if($gajiuniv){
            return ResponseFormatter::success($gajiuniv, 'Data Gaji Universitas Pegawai found');
        }
            return ResponseFormatter::error('Data Gaji Universitas Pegawai not found', 404);
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
    if($gaji_pusat)
    {
        $gajiuniv->where('gaji_pusat', 'like', '%'.$gaji_pusat.'%');

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
    if ($pegawai_id) {
        $gajiuniv->where('pegawai_id', $pegawai_id);
    }



    return ResponseFormatter::success(
        $gajiuniv->paginate($limit),
        'Data Gaji Universitas Pegawai Found'
    );
    }



    public function create(CreateGajiUnivRequest $request){
        try{

            $gajiuniversitas = new Gaji_Universitas;
            $total_gaji_univ = $gajiuniversitas->total_gaji_univ($request);
            // Create Gaji Universitas
            $gajiuniv = Gaji_Universitas::create([
                'gaji_pokok' => $request-> gaji_pokok,
                'tj_struktural' => $request-> tj_struktural,
                'tj_pres_kerja' => $request-> tj_pres_kerja,
                'u_lembur_hk' => $request-> u_lembur_hk,
                'u_lembur_hl' => $request-> u_lembur_hl,
                'trans_kehadiran' => $request-> trans_kehadiran,
                'tj_fungsional' => $request-> tj_fungsional,
                'gaji_pusat' => $request-> gaji_pusat,
                'tj_khs_istimewa' => $request-> tj_khs_istimewa,
                'tj_tambahan' => $request-> tj_tambahan,
                'honor_univ' => $request-> honor_univ,
                'tj_suami_istri' => $request-> tj_suami_istri,
                'tj_anak' => $request-> tj_anak,
                'total_gaji_univ' => $total_gaji_univ,
                'pegawai_id' => $request-> pegawai_id
            ]);
            if(!$gajiuniv){
                throw new Exception('Data Gaji Universitas Pegawai not created');
            }
            return ResponseFormatter::success($gajiuniv, 'Data Gaji Universitas Pegawai created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

        public function update(UpdateGajiUnivRequest $request, $id)
        {
            try {

                // Get Gaji Universitas
                $gajiuniv = Gaji_Universitas::find($id);

                // Check if Dosen Luar Biasa exists
                if(!$gajiuniv){
                    throw new Exception('Data Gaji Universitas Pegawai not found');
                }

                // Update Gaji Universitas
                $gajiuniversitas = new Gaji_Universitas;
                $total_gaji_univ = $gajiuniversitas->total_gaji_univ($request);
                $gajiuniv -> update([
                    'gaji_pokok' => $request-> gaji_pokok,
                    'tj_struktural' => $request-> tj_struktural,
                    'tj_pres_kerja' => $request-> tj_pres_kerja,
                    'u_lembur_hk' => $request-> u_lembur_hk,
                    'u_lembur_hl' => $request-> u_lembur_hl,
                    'trans_kehadiran' => $request-> trans_kehadiran,
                    'tj_fungsional' => $request-> tj_fungsional,
                    'gaji_pusat' => $request-> gaji_pusat,
                    'tj_khs_istimewa' => $request-> tj_khs_istimewa,
                    'tj_tambahan' => $request-> tj_tambahan,
                    'honor_univ' => $request-> honor_univ,
                    'tj_suami_istri' => $request-> tj_suami_istri,
                    'tj_anak' => $request-> tj_anak,
                    'total_gaji_univ' => $total_gaji_univ,
                    'pegawai_id' => $request-> pegawai_id
            ]);


            return ResponseFormatter::success($gajiuniv, 'Data Gaji Universitas Pegawai updated');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

    public function destroy($id){
        try{
            // Get Data Gaji Universitas
            $gajiuniv = Gaji_Universitas::find($id);

            // Check if Data Gaji Universitas exists
            if(!$gajiuniv){
                throw new Exception('Data Gaji Universitas Pegawai not found');
            }

            // Delete Data Gaji Universitas
            $gajiuniv->delete();

            return ResponseFormatter::success('Data Gaji Universitas Pegawai deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    }


