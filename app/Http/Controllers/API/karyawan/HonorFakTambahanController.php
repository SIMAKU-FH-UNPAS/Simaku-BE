<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use Illuminate\Http\Request;
use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\karyawan\Karyawan_Honor_Fakultas;
use App\Http\Controllers\API\karyawan\HitungPajakController;
use App\Http\Requests\karyawan\CreateHonorFakTambahanRequest;
use App\Http\Requests\karyawan\UpdateHonorFakTambahanRequest;

class HonorFakTambahanController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $karyawan_gaji_fakultas_id= $request->input('karyawan_gaji_fakultas_id');
        $nama_honor_FH = $request->input('nama_honor_FH');
        $besar_honor_FH = $request->input('besar_honor_FH');
        $month = $request->input('month');
        $year = $request->input('year');
        $limit = $request->input('limit', 10);

        $honorfaktambahanQuery = Karyawan_Honor_Fakultas::query();
 // Get single data
 if($id)
 {
     $honorfaktambahan= $honorfaktambahanQuery->find($id);

     if($honorfaktambahan){
         return ResponseFormatter::success($honorfaktambahan);
     }
         return ResponseFormatter::error('Data Honor Fakultas Tambahan Karyawan not found', 404);
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
  if($month && $year){
    $honorfaktambahan->whereMonth('created_at', $month)
    ->whereYear('created_at', $year);
}
  if($karyawan_gaji_fakultas_id)
  {
      $honorfaktambahan->where('karyawan_gaji_fakultas_id', $karyawan_gaji_fakultas_id);
  }

  return ResponseFormatter::success(
    $honorfaktambahan->paginate($limit),
    'Data Honor Fakultas Tambahan Karyawan Found'
);
}
public function create(CreateHonorFakTambahanRequest $request){
    try {
        // Create Honor Fakultas Tambahan
     $honorfaktambahan = Karyawan_Honor_Fakultas::create([
         'karyawan_gaji_fakultas_id' => $request-> karyawan_gaji_fakultas_id,
         'nama_honor_FH' => $request-> nama_honor_FH,
         'besar_honor_FH' => $request-> besar_honor_FH,
     ]);
     $gajiFakultas = Karyawan_Gaji_Fakultas::findOrFail($request-> karyawan_gaji_fakultas_id);
     $totalGajiFakultas = $gajiFakultas->total_gaji_fakultas + $request->besar_honor_FH;
     $gajiFakultas->update([
        'total_gaji_fakultas' => $totalGajiFakultas
    ]);
       // Memanggil Controller Hitung Pajak untuk update value rumus pajak
       $hitungPajakController = new HitungPajakController();
       $hitungPajakController->Hitung_Pajak_Fak($request, $honorfaktambahan->karyawan_gaji_fakultas_id); // Memanggil method Hitung_Pajak


     if(!$honorfaktambahan){
         throw new Exception('Data Honor Fakultas Tambahan Karyawan not created');
     }
     return ResponseFormatter::success($honorfaktambahan, 'Data Honor Fakultas Tambahan Karyawan created');
 }catch(Exception $e){
     return ResponseFormatter::error($e->getMessage(), 500);
 }
}
public function update(UpdateHonorFakTambahanRequest $request,  $id)
{
    try {

        // Get Honor Fakultas Tambahan
        $honorfaktambahan = Karyawan_Honor_Fakultas::find($id);

        // Check if Honor Fakultas Tambahan exists
        if(!$honorfaktambahan){
            throw new Exception('Data Honor Fakultas Karyawan not found');
        }

        // Update Honor Fakultas Tambahan
        $honorfaktambahan -> update([
            'karyawan_gaji_fakultas_id' => $request-> karyawan_gaji_fakultas_id,
            'nama_honor_FH' => $request-> nama_honor_FH,
            'besar_honor_FH' => $request-> besar_honor_FH,
    ]);
    $gajiFakultas = Karyawan_Gaji_Fakultas::findOrFail($request->karyawan_gaji_fakultas_id);
    $besar_honor_tambahan = Karyawan_Honor_Fakultas::where('karyawan_gaji_fakultas_id', $request->karyawan_gaji_fakultas_id)
    ->sum('besar_honor_FH');
    $totalGajiFakultas =
    $gajiFakultas->tj_tambahan+
    $gajiFakultas->honor_kinerja+
    $gajiFakultas->honor+
    $besar_honor_tambahan;

    $gajiFakultas->update([
       'total_gaji_fakultas' => $totalGajiFakultas
   ]);
   // Memanggil Controller Hitung Pajak untuk update value rumus pajak
   $hitungPajakController = new HitungPajakController();
   $hitungPajakController->Hitung_Pajak_Fak($request, $honorfaktambahan->karyawan_gaji_fakultas_id); // Memanggil method Hitung_Pajak


    return ResponseFormatter::success($honorfaktambahan, 'Data Honor Fakultas Karyawan updated');
}catch(Exception $e){
    return ResponseFormatter::error($e->getMessage(), 500);
}
}

public function destroy(Request $request,$id){
    try{
        // Get Data Honor Fakultas Tambahan
        $honorfaktambahan = Karyawan_Honor_Fakultas::find($id);

        // Check if Data Honor Fakultas Tambahan exists
        if(!$honorfaktambahan){
            throw new Exception('Data Honor Fakultas Tambahan Karyawan not found');
        }

        $gajiFakultas = $honorfaktambahan->gajifakultas;
        $totalGajiFakultas =  $gajiFakultas->total_gaji_fakultas - $honorfaktambahan->besar_honor_FH;
           $gajiFakultas->update([
            'total_gaji_fakultas' => $totalGajiFakultas
        ]);
          // Memanggil Controller Hitung Pajak untuk update value rumus pajak
     $hitungPajakController = new HitungPajakController();
    $hitungPajakController->Hitung_Pajak_Fak($request, $gajiFakultas->id); // Memanggil method Hitung_Pajak

        // Delete Data Honor Fakultas Tambahan
        $honorfaktambahan->delete();

        return ResponseFormatter::success('Data Honor Fakultas Tambahan Karyawan deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
