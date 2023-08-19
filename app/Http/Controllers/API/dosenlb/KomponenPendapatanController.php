<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan;
use App\Models\dosenlb\Doslb_Komponen_Pendapatan_Tambahan;
use App\Http\Requests\dosenlb\CreateKomponenPendapatanRequest;
use App\Http\Requests\dosenlb\UpdateKomponenPendapatanRequest;
use App\Http\Controllers\API\dosenlb\HitungPajakController;

class KomponenPendapatanController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $dosen_luar_biasa_id= $request->input('dosen_luar_biasa_id');
        $tj_tambahan = $request->input('tj_tambahan');
        $honor_kinerja = $request->input('honor_kinerja');
        $honor_mengajar = $request->input('honor_mengajar');
        $tj_guru_besar = $request->input('tj_guru_besar');
        $total_komponen_pendapatan= $request->input('total_komponen_pendapatan');
        $limit = $request->input('limit', 10);

        $komponenpendapatanQuery = Doslb_Komponen_Pendapatan::query();

                // Get single data
    if($id)
    {
        $komponenpendapatan= $komponenpendapatanQuery->find($id);

        if($komponenpendapatan){
            return ResponseFormatter::success($komponenpendapatan, 'Data Komponen Pendapatan Dosen Luar Biasa found');
        }
            return ResponseFormatter::error('Data Komponen Pendapatan Dosen Luar Biasa not found', 404);
    }

    //    Get multiple Data
    $komponenpendapatan = $komponenpendapatanQuery;

    // // Get by attribute
    if($tj_tambahan )
    {
        $komponenpendapatan->where('tj_tambahan ', 'like', '%'.$tj_tambahan .'%');

    }
    if($honor_kinerja)
    {
        $komponenpendapatan->where('honor_kinerja', 'like', '%'.$honor_kinerja.'%');

    }
    if($honor_mengajar)
    {
        $komponenpendapatan->where('honor_mengajar', 'like', '%'.$honor_mengajar.'%');

    }
    if($tj_guru_besar)
    {
        $komponenpendapatan->where('tj_guru_besar', 'like', '%'.$tj_guru_besar.'%');

    }
    if($total_komponen_pendapatan)
    {
        $komponenpendapatan->where('total_komponen_pendapatan', 'like', '%'.$total_komponen_pendapatan.'%');

    }
    if ($dosen_luar_biasa_id) {
        $komponenpendapatan->where('dosen_luar_biasa_id', $dosen_luar_biasa_id);
    }



    return ResponseFormatter::success(
        $komponenpendapatan->paginate($limit),
        'Data Komponen Pendapatan Dosen Luar Biasa Found'
    );
}
public function create(CreateKomponenPendapatanRequest $request){
    try{
        $dosen_luar_biasa_id = $request->dosen_luar_biasa_id;

        // Get the current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;


        // Check if data already exists for the current month and year
        $existingkomponenpendapatan = Doslb_Komponen_Pendapatan::where('dosen_luar_biasa_id', $dosen_luar_biasa_id)
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->first();

    if ($existingkomponenpendapatan) {
        return ResponseFormatter::error('Data Komponen Pendapatan Dosen Luar Biasa for this month already exists', 400);
    }
        $komponenpendapatan = new Doslb_Komponen_Pendapatan;
        $total_komponen_pendapatan = $komponenpendapatan->total_komponen_pendapatan($request);
        // Create Komponen Pendapatan
        $komponenpendapatan = Doslb_Komponen_Pendapatan::create([
            'dosen_luar_biasa_id' => $request-> dosen_luar_biasa_id,
            'tj_tambahan' => $request-> tj_tambahan,
            'honor_kinerja' => $request-> honor_kinerja,
            'honor_mengajar' => $request-> honor_mengajar,
            'tj_guru_besar' => $request-> tj_guru_besar,
            'total_komponen_pendapatan' => $total_komponen_pendapatan,
        ]);
        if(!$komponenpendapatan){
            throw new Exception('Data Komponen Pendapatan Dosen Luar Biasa not created');
        }
        return ResponseFormatter::success($komponenpendapatan, 'Data Komponen Pendapatan Dosen Luar Biasa created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }
    public function update(UpdateKomponenPendapatanRequest $request, $id)
    {
        try {
            // Get Komponen Pendapatan
            $komponenpendapatan = Doslb_Komponen_Pendapatan::find($id);
            // Check if Gaji Fakultas exists
            if(!$komponenpendapatan){
                throw new Exception('Data Komponen Pendapatan Dosen Luar Biasa not found');
            }
             // Menghitung total_komponen_pendapatan tanpa mempertimbangkan besar_komponen
            $total_komponen_pendapatan =
            $request->tj_tambahan +
            $request->honor_kinerja +
            $request->honor_mengajar +
            $request->tj_guru_besar;

            // Mengecek apakah ada data besar_komponen dari tabel Komponen_Pendapatan_Tambahan
            $komponen_pendapatan_tambahan = Doslb_Komponen_Pendapatan_Tambahan::where('doslb_pendapatan_id',$id)->whereNull('deleted_at')->get();
            if ($komponen_pendapatan_tambahan){
                foreach($komponen_pendapatan_tambahan as $komponen){
                    // Jika ada, tambahkan besar_komponen ke total_komponen_pendapatan
                $total_komponen_pendapatan += $komponen->besar_komponen;
            }
                }
           // Update Gaji Fakultas
            $komponenpendapatan -> update([
                'dosen_luar_biasa_id' => $request-> dosen_luar_biasa_id,
                'tj_tambahan' => $request-> tj_tambahan,
                'honor_kinerja' => $request-> honor_kinerja,
                'honor_mengajar' => $request-> honor_mengajar,
                'tj_guru_besar' => $request-> tj_guru_besar,
                'total_komponen_pendapatan' => $total_komponen_pendapatan,
        ]);
           // Memanggil Controller Hitung Pajak untuk update value rumus pajak
           $hitungPajakController = new HitungPajakController();
           $hitungPajakController->Hitung_Pajak_Pendapatan($request,$komponenpendapatan->id); // Memanggil method Hitung_Pajak

        return ResponseFormatter::success($komponenpendapatan, 'Data Komponen Pendapatan Dosen Luar Biasa updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
 try {
        // Get Data Komponen Pendapatan
        $komponenpendapatan = Doslb_Komponen_Pendapatan::findOrFail($id);

        // Check if Data Komponen Pendapatan exists
        if (!$komponenpendapatan) {
            throw new Exception('Data Komponen Pendapatan Dosen Luar Biasa not found');
        }

        // Delete the related komponenpendapatantambahan() records
        $komponenpendapatan->komponenpendapatantambahan()->delete();
        // Use Eloquent destroy to trigger cascading delete
        $komponenpendapatan->delete();

        return ResponseFormatter::success('Data Komponen Pendapatan Dosen Luar Biasa deleted');

    } catch (Exception $e) {
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

}

