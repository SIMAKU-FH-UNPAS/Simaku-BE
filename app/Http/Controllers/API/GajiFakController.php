<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Models\Gaji_Fakultas;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGajiFakRequest;
use App\Http\Requests\UpdateGajiFakRequest;
use App\Models\Honor_Fakultas_Tambahan;

class GajiFakController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $pegawai_id= $request->input('pegawai_id');
        $tj_tambahan = $request->input('tj_tambahan');
        $honor_kinerja = $request->input('honor_kinerja');
        $honor_klb_mengajar = $request->input('honor_klb_mengajar');
        $honor_mengajar_DPK = $request->input('honor_mengajar_DPK');
        $peny_honor_mengajar = $request->input('peny_honor_mengajar');
        $tj_guru_besar = $request->input('tj_guru_besar');
        $total_gaji_fakultas= $request->input('total_gaji_fakultas');
        $limit = $request->input('limit', 10);

        $gajifakQuery = Gaji_Fakultas::query();

                // Get single data
    if($id)
    {
        $gajifak= $gajifakQuery->find($id);

        if($gajifak){
            return ResponseFormatter::success($gajifak, 'Data Gaji Fakultas Pegawai found');
        }
            return ResponseFormatter::error('Data Gaji Fakultas Pegawai not found', 404);
    }

    //    Get multiple Data
    $gajifak = $gajifakQuery;

    // // Get by attribute
    if($tj_tambahan )
    {
        $gajifak->where('tj_tambahan ', 'like', '%'.$tj_tambahan .'%');

    }
    if($honor_kinerja)
    {
        $gajifak->where('honor_kinerja', 'like', '%'.$honor_kinerja.'%');

    }
    if($honor_klb_mengajar)
    {
        $gajifak->where('honor_klb_mengajar', 'like', '%'.$honor_klb_mengajar.'%');

    }
    if($honor_mengajar_DPK)
    {
        $gajifak->where('honor_mengajar_DPK', 'like', '%'.$honor_mengajar_DPK.'%');

    }
    if($peny_honor_mengajar)
    {
        $gajifak->where('peny_honor_mengajar', 'like', '%'.$peny_honor_mengajar.'%');

    }
    if($tj_guru_besar)
    {
        $gajifak->where('tj_guru_besar', 'like', '%'.$tj_guru_besar.'%');

    }
    if($total_gaji_fakultas)
    {
        $gajifak->where('total_gaji_fakultas', 'like', '%'.$total_gaji_fakultas.'%');

    }
    if ($pegawai_id) {
        $gajifak->where('pegawai_id', $pegawai_id);
    }



    return ResponseFormatter::success(
        $gajifak->paginate($limit),
        'Data Gaji Fakultas Pegawai Found'
    );
}
public function create(CreateGajiFakRequest $request){
    try{
        $gajifakultas = new Gaji_Fakultas;
        $total_gaji_fakultas = $gajifakultas->total_gaji_fakultas($request);
        // Create Gaji Fakultas
        $gajifak = Gaji_Fakultas::create([
            'pegawai_id' => $request-> pegawai_id,
            'tj_tambahan' => $request-> tj_tambahan,
            'honor_kinerja' => $request-> honor_kinerja,
            'honor_klb_mengajar' => $request-> honor_klb_mengajar,
            'honor_mengajar_DPK' => $request-> honor_mengajar_DPK,
            'peny_honor_mengajar' => $request-> peny_honor_mengajar,
            'tj_guru_besar' => $request-> tj_guru_besar,
            'total_gaji_fakultas' => $total_gaji_fakultas,
        ]);
        if(!$gajifak){
            throw new Exception('Data Gaji Fakultas Pegawai not created');
        }
        return ResponseFormatter::success($gajifak, 'Data Gaji Fakultas Pegawai created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }
    public function update(UpdateGajiFakRequest $request, $id)
    {
        try {
            // Get Gaji Fakultas
            $gajifak = Gaji_Fakultas::find($id);
            // Check if Gaji Fakultas exists
            if(!$gajifak){
                throw new Exception('Data Gaji Fakultas Pegawai not found');
            }
             // Menghitung total_gaji_fakultas tanpa mempertimbangkan besar_honor_FH
            $total_gaji_fakultas =
            $request->tj_tambahan +
            $request->honor_kinerja +
            $request->honor_klb_mengajar +
            $request->honor_mengajar_DPK +
            $request->peny_honor_mengajar +
            $request->tj_guru_besar;

            // Mengecek apakah ada data besar_honor_FH dari tabel Honor_Fakultas_Tambahan
            $honor_fakultas_tambahan = Honor_Fakultas_Tambahan::where('gaji_fakultas_id',$id)->whereNull('deleted_at')->get();
            if ($honor_fakultas_tambahan){
                foreach($honor_fakultas_tambahan as $honorfak){
                    // Jika ada, tambahkan besar_honor_FK ke total_gaji_fakultas
                $total_gaji_fakultas += $honorfak->besar_honor_FH;
            }
                }
           // Update Gaji Fakultas
            $gajifak -> update([
                'pegawai_id' => $request-> pegawai_id,
                'tj_tambahan' => $request-> tj_tambahan,
                'honor_kinerja' => $request-> honor_kinerja,
                'honor_klb_mengajar' => $request-> honor_klb_mengajar,
                'honor_mengajar_DPK' => $request-> honor_mengajar_DPK,
                'peny_honor_mengajar' => $request-> peny_honor_mengajar,
                'tj_guru_besar' => $request-> tj_guru_besar,
                'total_gaji_fakultas' => $total_gaji_fakultas,
        ]);
        return ResponseFormatter::success($gajifak, 'Data Gaji Fakultas Pegawai updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
 try {
        // Get Data Gaji Fakultas
        $gajifak = Gaji_Fakultas::findOrFail($id);

        // Check if Data Gaji Fakultas exists
        if (!$gajifak) {
            throw new Exception('Data Gaji Fakultas Pegawai not found');
        }

        // Delete the related Honor_Fakultas_Tambahan records
        $gajifak->honorfakultastambahan()->delete();
        // Use Eloquent destroy to trigger cascading delete
        $gajifak->delete();

        return ResponseFormatter::success('Data Gaji Fakultas Pegawai deleted');

    } catch (Exception $e) {
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

}
