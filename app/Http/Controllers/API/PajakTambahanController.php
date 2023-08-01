<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\Pajak_Tambahan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreatePajakTambahanRequest;
use App\Http\Requests\UpdatePajakTambahanRequest;
use App\Models\Pajak;

class PajakTambahanController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $pajak_id= $request->input('pajak_id');
        $nama_pajak = $request->input('nama_pajak');
        $besar_pajak = $request->input('besar_pajak');
        $limit = $request->input('limit', 10);

        $pajaktambahanQuery = Pajak_Tambahan::query();
 // Get single data
 if($id)
 {
     $pajaktambahan= $pajaktambahanQuery->find($id);

     if($pajaktambahan){
         return ResponseFormatter::success($pajaktambahan, 'Data Pajak Tambahan Pegawai found');
     }
         return ResponseFormatter::error('Data Pajak Tambahan Pegawai not found', 404);
 }

  //    Get multiple Data
  $pajaktambahan = $pajaktambahanQuery;

  // // Get by attribute
  if($nama_pajak)
  {
      $pajaktambahan->where('nama_pajak', 'like', '%'.$nama_pajak.'%');
  }
  if($besar_pajak)
  {
    $pajaktambahan->where('besar_pajak', 'like', '%'.$besar_pajak.'%');

  }
  if($pajak_id)
  {
      $pajaktambahan->where('pajak_id', $pajak_id);
  }

  return ResponseFormatter::success(
    $pajaktambahan->paginate($limit),
    'Data Pajak Tambahan Pegawai Found'
);
}
public function create(CreatePajakTambahanRequest $request, $pajak_id){
    try {
        $pajak = Pajak::findOrFail($pajak_id);
        // Create Pajak Tambahan
     $pajaktambahan = Pajak_Tambahan::create([
         'pajak_id' => $request-> pajak_id,
         'nama_pajak' => $request-> nama_pajak,
         'besar_pajak' => $request-> besar_pajak,
     ]);
     $totalpajak = $pajak->total_pajak + $request->besar_pajak;
     $pajak->update([
        'total_pajak' => $totalpajak
    ]);

     if(!$pajaktambahan){
         throw new Exception('Data Pajak Tambahan Pegawai not created');
     }
     return ResponseFormatter::success($pajaktambahan, 'Data Pajak Tambahan Pegawai created');
 }catch(Exception $e){
     return ResponseFormatter::error($e->getMessage(), 500);
 }
}
public function update(UpdatePajakTambahanRequest $request,  $id, $pajak_id)
{
    try {

        // Get Pajak Tambahan
        $pajaktambahan = Pajak_Tambahan::find($id);

        // Check if Pajak Tambahan exists
        if(!$pajaktambahan){
            throw new Exception('Data Pajak Tambahan Pegawai not found');
        }

        // Update Pajak Tambahan
        $pajaktambahan -> update([
            'pajak_id' => $request-> pajak_id,
            'nama_pajak' => $request-> nama_pajak,
            'besar_pajak' => $request-> besar_pajak,
    ]);
    $pajak = Pajak::findOrFail($pajak_id);
    $total_pajak =
    $pajak->pensiun+
    $pajak->bruto_pajak+
    $pajak->bruto_murni+
    $pajak->biaya_jabatan+
    $pajak->as_bumi_putera+
    $pajak->dplk_pensiun+
    $pajak->jml_pot_kn_pajak+
    $pajak->set_potongan_kn_pajak+
    $pajak->ptkp+
    $pajak->pkp+
    $pajak->pajak_pph21+
    $pajak->jml_set_pajak+
    $pajak->pot_tk_kena_pajak+
    $request->besar_pajak;

    $pajak->update([
       'total_pajak' => $total_pajak
   ]);


    return ResponseFormatter::success($pajaktambahan, 'Data Pajak Tambahan Pegawai updated');
}catch(Exception $e){
    return ResponseFormatter::error($e->getMessage(), 500);
}
}

public function destroy($id){
    try{
        // Get Data Pajak Tambahan
        $pajaktambahan = Pajak_Tambahan::find($id);

        // Check if Data Pajak Tambahan exists
        if(!$pajaktambahan){
            throw new Exception('Data Pajak Tambahan Pegawai not found');
        }

        $pajak = $pajaktambahan->pajak;
        $totalpajak =  $pajak->total_pajak - $pajaktambahan->besar_pajak;
           $pajak->update([
            'total_pajak' => $totalpajak
        ]);
        // Delete Data Honor Fakultas Tambahan
        $pajaktambahan->delete();

        return ResponseFormatter::success('Data Pajak Tambahan Pegawai deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
