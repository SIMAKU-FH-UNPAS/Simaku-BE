<?php

namespace App\Http\Controllers\API\dosentetap;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\dosentetap\Dostap_Gaji_Fakultas;
use App\Models\dosentetap\Dostap_Honor_Fakultas;
use App\Http\Requests\CreateGajiFakRequest;
use App\Http\Requests\UpdateGajiFakRequest;
use App\Http\Controllers\API\dosentetap\HitungPajakController;

class GajiFakController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $dosen_tetap_id= $request->input('dosen_tetap_id');
        $tj_tambahan = $request->input('tj_tambahan');
        $honor_kinerja = $request->input('honor_kinerja');
        $honor_klb_mengajar = $request->input('honor_klb_mengajar');
        $honor_mengajar_DPK = $request->input('honor_mengajar_DPK');
        $peny_honor_mengajar = $request->input('peny_honor_mengajar');
        $tj_guru_besar = $request->input('tj_guru_besar');
        $honor = $request->input('honor');
        $total_gaji_fakultas= $request->input('total_gaji_fakultas');
        $limit = $request->input('limit', 10);

        $gajifakQuery = Dostap_Gaji_Fakultas::query();

                // Get single data
    if($id)
    {
        $gajifak= $gajifakQuery->find($id);

        if($gajifak){
            return ResponseFormatter::success($gajifak, 'Data Gaji Fakultas Dosen Tetap found');
        }
            return ResponseFormatter::error('Data Gaji Fakultas Dosen Tetap not found', 404);
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
    if($honor)
    {
        $gajifak->where('honor', 'like', '%'.$honor.'%');

    }
    if($total_gaji_fakultas)
    {
        $gajifak->where('total_gaji_fakultas', 'like', '%'.$total_gaji_fakultas.'%');

    }
    if ($dosen_tetap_id) {
        $gajifak->where('dosen_tetap_id', $dosen_tetap_id);
    }



    return ResponseFormatter::success(
        $gajifak->paginate($limit),
        'Data Gaji Fakultas Dosen Tetap Found'
    );
}
public function create(CreateGajiFakRequest $request){
    try{
        $dosen_tetap_id = $request->dosen_tetap_id;

        // Get the current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;


        // Check if data already exists for the current month and year
        $existingGajiFak = Dostap_Gaji_Fakultas::where('dosen_tetap_id', $dosen_tetap_id)
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->first();

    if ($existingGajiFak) {
        return ResponseFormatter::error('Data Gaji Fakultas Dosen Tetap for this month already exists', 400);
    }
        $gajifakultas = new Dostap_Gaji_Fakultas;
        $total_gaji_fakultas = $gajifakultas->total_gaji_fakultas($request);
        // Create Gaji Fakultas
        $gajifak = Dostap_Gaji_Fakultas::create([
            'dosen_tetap_id' => $request-> dosen_tetap_id,
            'tj_tambahan' => $request-> tj_tambahan,
            'honor_kinerja' => $request-> honor_kinerja,
            'honor_klb_mengajar' => $request-> honor_klb_mengajar,
            'honor_mengajar_DPK' => $request-> honor_mengajar_DPK,
            'peny_honor_mengajar' => $request-> peny_honor_mengajar,
            'tj_guru_besar' => $request-> tj_guru_besar,
            'honor' => $request-> honor,
            'total_gaji_fakultas' => $total_gaji_fakultas,
        ]);
        if(!$gajifak){
            throw new Exception('Data Gaji Fakultas Dosen Tetap not created');
        }
        return ResponseFormatter::success($gajifak, 'Data Gaji Fakultas Dosen Tetap created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }
    public function update(UpdateGajiFakRequest $request, $id)
    {
        try {
            // Get Gaji Fakultas
            $gajifak = Dostap_Gaji_Fakultas::find($id);
            // Check if Gaji Fakultas exists
            if(!$gajifak){
                throw new Exception('Data Gaji Fakultas Dosen Tetap not found');
            }
             // Menghitung total_gaji_fakultas tanpa mempertimbangkan besar_honor_FH
            $total_gaji_fakultas =
            $request->tj_tambahan +
            $request->honor_kinerja +
            $request->honor_klb_mengajar +
            $request->honor_mengajar_DPK +
            $request->peny_honor_mengajar +
            $request->tj_guru_besar+
            $request->honor;

            // Mengecek apakah ada data besar_honor_FH dari tabel Honor_Fakultas_Tambahan
            $honor_fakultas_tambahan = Dostap_Honor_Fakultas::where('dostap_gaji_fakultas_id',$id)->whereNull('deleted_at')->get();
            if ($honor_fakultas_tambahan){
                foreach($honor_fakultas_tambahan as $honorfak){
                    // Jika ada, tambahkan besar_honor_FK ke total_gaji_fakultas
                $total_gaji_fakultas += $honorfak->besar_honor_FH;
            }
                }
           // Update Gaji Fakultas
            $gajifak -> update([
                'dosen_tetap_id' => $request-> dosen_tetap_id,
                'tj_tambahan' => $request-> tj_tambahan,
                'honor_kinerja' => $request-> honor_kinerja,
                'honor_klb_mengajar' => $request-> honor_klb_mengajar,
                'honor_mengajar_DPK' => $request-> honor_mengajar_DPK,
                'peny_honor_mengajar' => $request-> peny_honor_mengajar,
                'tj_guru_besar' => $request-> tj_guru_besar,
                'honor' => $request-> honor,
                'total_gaji_fakultas' => $total_gaji_fakultas,
        ]);
           // Memanggil Controller Hitung Pajak untuk update value rumus pajak
           $hitungPajakController = new HitungPajakController();
           $hitungPajakController->Hitung_Pajak_Fak($request,$gajifak->id); // Memanggil method Hitung_Pajak

        return ResponseFormatter::success($gajifak, 'Data Gaji Fakultas Dosen Tetap updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
 try {
        // Get Data Gaji Fakultas
        $gajifak = Dostap_Gaji_Fakultas::findOrFail($id);

        // Check if Data Gaji Fakultas exists
        if (!$gajifak) {
            throw new Exception('Data Gaji Fakultas Dosen Tetap not found');
        }

        // Delete the related Honor_Fakultas_Tambahan records
        $gajifak->honorfakultastambahan()->delete();
        // Use Eloquent destroy to trigger cascading delete
        $gajifak->delete();

        return ResponseFormatter::success('Data Gaji Fakultas Dosen Tetap deleted');

    } catch (Exception $e) {
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

}
