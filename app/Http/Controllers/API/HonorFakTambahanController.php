<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Honor_Fakultas_Tambahan;
use App\Http\Requests\CreateHonorFakTambahanRequest;
use App\Http\Requests\UpdateHonorFakTambahanRequest;
use App\Models\Gaji_Fakultas;

class HonorFakTambahanController extends Controller
{
   public function fetch(Request $request){
        $id = $request->input('id');
        $gaji_fakultas_id= $request->input('gaji_fakultas_id');
        $nama_honor_FH = $request->input('nama_honor_FH');
        $besar_honor_FH = $request->input('besar_honor_FH');
        $limit = $request->input('limit', 10);

        $honorfaktambahanQuery = Honor_Fakultas_Tambahan::query();
 // Get single data
 if($id)
 {
     $honorfaktambahan= $honorfaktambahanQuery->find($id);

     if($honorfaktambahan){
         return ResponseFormatter::success($honorfaktambahan, 'Data Honor Fakultas Tambahan Pegawai found');
     }
         return ResponseFormatter::error('Data Honor Fakultas Tambahan Pegawai not found', 404);
 }

  //    Get multiple Data
  $honorfaktambahan = $honorfaktambahanQuery;

  // // Get by attribute
  if($nama_honor_FH)
  {
      $honorfaktambahan->where('nama_honor_FH', 'like', '%'.$nama_honor_FH.'%');
  }
  if($besar_honor_FH)
  {
    $honorfaktambahan->where('besar_honor_FH', 'like', '%'.$besar_honor_FH.'%');

  }
  if($gaji_fakultas_id)
  {
      $honorfaktambahan->where('gaji_fakultas_id', $gaji_fakultas_id);
  }

  return ResponseFormatter::success(
    $honorfaktambahan->paginate($limit),
    'Data Honor Fakultas Tambahan Pegawai Found'
);
}
public function create(CreateHonorFakTambahanRequest $request, $gaji_fakultas_id){
    try {
        $gajiFakultas = Gaji_Fakultas::findOrFail($gaji_fakultas_id);
        // Create Honor Fakultas Tambahan
     $honorfaktambahan = Honor_Fakultas_Tambahan::create([
         'gaji_fakultas_id' => $request-> gaji_fakultas_id,
         'nama_honor_FH' => $request-> nama_honor_FH,
         'besar_honor_FH' => $request-> besar_honor_FH,
     ]);
     $totalGajiFakultas = $gajiFakultas->total_gaji_fakultas + $request->besar_honor_FH;
     $gajiFakultas->update([
        'total_gaji_fakultas' => $totalGajiFakultas
    ]);

     if(!$honorfaktambahan){
         throw new Exception('Data Honor Fakultas Tambahan Pegawai not created');
     }
     return ResponseFormatter::success($honorfaktambahan, 'Data Honor Fakultas Tambahan Pegawai created');
 }catch(Exception $e){
     return ResponseFormatter::error($e->getMessage(), 500);
 }
}
public function update(UpdateHonorFakTambahanRequest $request,  $id, $gaji_fakultas_id)
{
    try {

        // Get Honor Fakultas Tambahan
        $honorfaktambahan = Honor_Fakultas_Tambahan::find($id);

        // Check if Honor Fakultas Tambahan exists
        if(!$honorfaktambahan){
            throw new Exception('Data Honor Fakultas Pegawai not found');
        }

        // Update Honor Fakultas Tambahan
        $honorfaktambahan -> update([
            'gaji_fakultas_id' => $request-> gaji_fakultas_id,
            'nama_honor_FH' => $request-> nama_honor_FH,
            'besar_honor_FH' => $request-> besar_honor_FH,
    ]);
    $gajiFakultas = Gaji_Fakultas::findOrFail($gaji_fakultas_id);
    $totalGajiFakultas =
    $gajiFakultas->tj_tambahan+
    $gajiFakultas->honor_kinerja+
    $gajiFakultas->honor_klb_mengajar+
    $gajiFakultas->honor_mengajar_DPK+
    $gajiFakultas->peny_honor_mengajar+
    $gajiFakultas->tj_guru_besar+
    $request->besar_honor_FH;

    $gajiFakultas->update([
       'total_gaji_fakultas' => $totalGajiFakultas
   ]);


    return ResponseFormatter::success($honorfaktambahan, 'Data Honor Fakultas Pegawai updated');
}catch(Exception $e){
    return ResponseFormatter::error($e->getMessage(), 500);
}
}

public function destroy($id){
    try{
        // Get Data Honor Fakultas Tambahan
        $honorfaktambahan = Honor_Fakultas_Tambahan::find($id);

        // Check if Data Honor Fakultas Tambahan exists
        if(!$honorfaktambahan){
            throw new Exception('Data Honor Fakultas Tambahan Pegawai not found');
        }

        $gajiFakultas = $honorfaktambahan->gajifakultas;
        $totalGajiFakultas =  $gajiFakultas->total_gaji_fakultas - $honorfaktambahan->besar_honor_FH;
           $gajiFakultas->update([
            'total_gaji_fakultas' => $totalGajiFakultas
        ]);
        // Delete Data Honor Fakultas Tambahan
        $honorfaktambahan->delete();

        return ResponseFormatter::success('Data Honor Fakultas Tambahan Pegawai deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}

