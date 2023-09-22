<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use Illuminate\Http\Request;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan_Tambahan;
use App\Http\Controllers\API\dosenlb\HitungPajakController;
use App\Http\Requests\dosenlb\CreateKomponenPendapatanTambahanRequest;
use App\Http\Requests\dosenlb\UpdateKomponenPendapatanTambahanRequest;

class KomponenPendapatanTambahanController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $doslb_pendapatan_id= $request->input('doslb_pendapatan_id');
        $nama_komponen = $request->input('nama_komponen');
        $besar_komponen = $request->input('besar_komponen');
        $month = $request->input('month');
        $year = $request->input('year');
        $limit = $request->input('limit', 10);

        $komponenpendapatantambahanQuery = Doslb_Komponen_Pendapatan_Tambahan::query();
 // Get single data
 if($id)
 {
     $komponenpendapatantambahan= $komponenpendapatantambahanQuery->find($id);

     if($komponenpendapatantambahan){
         return ResponseFormatter::success($komponenpendapatantambahan);
     }
         return ResponseFormatter::error('Data Komponen Pendapatan Tambahan Dosen Luar Biasa not found', 404);
 }

  //    Get multiple Data
  $komponenpendapatantambahan = $komponenpendapatantambahanQuery;

  // // Get by attribute
  if($nama_komponen)
  {
    $komponenpendapatantambahan->where('nama_komponen', 'like', '%'.$nama_komponen.'%');
  }
  if($besar_komponen)
  {
    $komponenpendapatantambahan->where('besar_komponen', 'like', '%'.$besar_komponen.'%');

  }
  if($month && $year){
    $komponenpendapatantambahan->whereMonth('created_at', $month)
    ->whereYear('created_at', $year);
}
  if($doslb_pendapatan_id)
  {
    $komponenpendapatantambahan->where('doslb_pendapatan_id', $doslb_pendapatan_id);
  }

  return ResponseFormatter::success(
    $komponenpendapatantambahan->paginate($limit),
    'Data Komponen Pendapatan Tambahan Dosen Luar Biasa Found'
);
}
public function create(CreateKomponenPendapatanTambahanRequest $request){
    try {
        // Create Komponen Tambahan
        $komponenpendapatantambahan= Doslb_Komponen_Pendapatan_Tambahan::create([
         'doslb_pendapatan_id' => $request-> doslb_pendapatan_id,
         'nama_komponen' => $request-> nama_komponen,
         'besar_komponen' => $request-> besar_komponen,
     ]);
     $komponenpendapatan = Doslb_Komponen_Pendapatan::findOrFail($request-> doslb_pendapatan_id);
     $totalkomponenpendapatan = $komponenpendapatan->total_komponen_pendapatan + $request->besar_komponen;
     $komponenpendapatan->update([
        'total_komponen_pendapatan' => $totalkomponenpendapatan
    ]);
       // Memanggil Controller Hitung Pajak untuk update value rumus pajak
       $hitungPajakController = new HitungPajakController();
       $hitungPajakController->Hitung_Pajak_Pendapatan($request, $komponenpendapatantambahan->doslb_pendapatan_id); // Memanggil method Hitung_Pajak


     if(!$komponenpendapatantambahan){
         throw new Exception('Data Komponen Pendapatan Tambahan Dosen Luar Biasa not created');
     }
     return ResponseFormatter::success($komponenpendapatantambahan, 'Data Komponen Pendapatan Tambahan Dosen Luar Biasa created');
 }catch(Exception $e){
     return ResponseFormatter::error($e->getMessage(), 500);
 }
}
public function update(UpdateKomponenPendapatanTambahanRequest $request,  $id)
{
    try {

        // Get Komponen Tambahan
        $komponenpendapatantambahan = Doslb_Komponen_Pendapatan_Tambahan::find($id);

        // Check if Honor Fakultas Tambahan exists
        if(!$komponenpendapatantambahan){
            throw new Exception('Data Komponen Pendapatan Dosen Luar Biasa not found');
        }

        // Update Komponen Tambahan
        $komponenpendapatantambahan-> update([
            'doslb_pendapatan_id' => $request-> doslb_pendapatan_id,
            'nama_komponen' => $request-> nama_komponen,
            'besar_komponen' => $request-> besar_komponen,
    ]);
    $komponenpendapatan = Doslb_Komponen_Pendapatan::findOrFail($request-> doslb_pendapatan_id);
    $besar_komponen_tambahan = Doslb_Komponen_Pendapatan_Tambahan::where('doslb_pendapatan_id', $request->doslb_pendapatan_id)
    ->sum('besar_komponen');
    $totalkomponenpendapatan =
    $komponenpendapatan->tj_tambahan+
    $komponenpendapatan->honor_kinerja+
    $komponenpendapatan->honor_mengajar+
    $komponenpendapatan->tj_guru_besar+
    $besar_komponen_tambahan;

    $komponenpendapatan->update([
       'total_komponen_pendapatan' => $totalkomponenpendapatan
   ]);
   // Memanggil Controller Hitung Pajak untuk update value rumus pajak
   $hitungPajakController = new HitungPajakController();
   $hitungPajakController->Hitung_Pajak_Pendapatan($request, $komponenpendapatantambahan->doslb_pendapatan_id); // Memanggil method Hitung_Pajak


    return ResponseFormatter::success($komponenpendapatantambahan, 'Data Komponen Pendapatan Dosen Luar Biasa updated');
}catch(Exception $e){
    return ResponseFormatter::error($e->getMessage(), 500);
}
}

public function destroy(Request $request,$id){
    try{
        // Get Data Komponen Tambahan
        $komponenpendapatantambahan = Doslb_Komponen_Pendapatan_Tambahan::find($id);

        // Check if Data Komponen Tambahan exists
        if(!$komponenpendapatantambahan){
            throw new Exception('Data Komponen Pendapatan Tambahan Dosen Luar Biasa not found');
        }

        $komponenpendapatan = $komponenpendapatantambahan->komponenpendapatan;
        $totalkomponenpendapatan =  $komponenpendapatan->total_komponen_pendapatan - $komponenpendapatantambahan->besar_komponen;
           $komponenpendapatan->update([
            'total_komponen_pendapatan' => $totalkomponenpendapatan
        ]);
          // Memanggil Controller Hitung Pajak untuk update value rumus pajak
     $hitungPajakController = new HitungPajakController();
    $hitungPajakController->Hitung_Pajak_Pendapatan($request, $komponenpendapatan->id); // Memanggil method Hitung_Pajak

        // Delete Data Honor Fakultas Tambahan
        $komponenpendapatantambahan->delete();

        return ResponseFormatter::success('Data Komponen Pendapatan Tambahan Dosen Luar Biasa deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
