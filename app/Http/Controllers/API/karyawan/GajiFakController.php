<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use App\Models\karyawan\Karyawan_Honor_Fakultas;
use App\Http\Requests\karyawan\CreateGajiFakRequest;
use App\Http\Requests\karyawan\UpdateGajiFakRequest;
use App\Http\Controllers\API\karyawan\HitungPajakController;

class GajiFakController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $karyawan_id= $request->input('karyawan_id');
        $tj_tambahan = $request->input('tj_tambahan');
        $honor_kinerja = $request->input('honor_kinerja');
        $honor = $request->input('honor');
        $total_gaji_fakultas= $request->input('total_gaji_fakultas');
        $month = $request->input('month');
        $year = $request->input('year');
        $limit = $request->input('limit', 10);

        $gajifakQuery = Karyawan_Gaji_Fakultas::query();

                // Get single data
    if($id)
    {
        $gajifak= $gajifakQuery->find($id);

        if($gajifak){
            return ResponseFormatter::success($gajifak, 'Data Gaji Fakultas Karyawan found');
        }
            return ResponseFormatter::error('Data Gaji Fakultas Karyawan not found', 404);
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
    if($honor)
    {
        $gajifak->where('honor', 'like', '%'.$honor.'%');

    }
    if($total_gaji_fakultas)
    {
        $gajifak->where('total_gaji_fakultas', 'like', '%'.$total_gaji_fakultas.'%');

    }
    if($month && $year){
        $gajifak->whereMonth('created_at', $month)
        ->whereYear('created_at', $year);
    }
    if ($karyawan_id) {
        $gajifak->where('karyawan_id', $karyawan_id);
    }



    return ResponseFormatter::success(
        $gajifak->paginate($limit),
        'Data Gaji Fakultas Karyawan Found'
    );
}
public function create(CreateGajiFakRequest $request){
    try{
        $karyawan_id = $request->karyawan_id;

        // Get the current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;


        // Check if data already exists for the current month and year
        $existingGajiFak = Karyawan_Gaji_Fakultas::where('karyawan_id', $karyawan_id)
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->first();

    if ($existingGajiFak) {
        return ResponseFormatter::error('Data Gaji Fakultas Karyawan for this month already exists', 400);
    }
        $gajifakultas = new Karyawan_Gaji_Fakultas;
        $total_gaji_fakultas = $gajifakultas->total_gaji_fakultas($request);
        // Create Gaji Fakultas
        $gajifak = Karyawan_Gaji_Fakultas::create([
            'karyawan_id' => $request-> karyawan_id,
            'tj_tambahan' => $request-> tj_tambahan,
            'honor_kinerja' => $request-> honor_kinerja,
            'honor' => $request-> honor,
            'total_gaji_fakultas' => $total_gaji_fakultas,
        ]);
        if(!$gajifak){
            throw new Exception('Data Gaji Fakultas Karyawan not created');
        }
        return ResponseFormatter::success($gajifak, 'Data Gaji Fakultas Karyawan created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }
    public function update(UpdateGajiFakRequest $request, $id)
    {
        try {
            // Get Gaji Fakultas
            $gajifak = Karyawan_Gaji_Fakultas::find($id);
            // Check if Gaji Fakultas exists
            if(!$gajifak){
                throw new Exception('Data Gaji Fakultas Karyawan not found');
            }
             // Menghitung total_gaji_fakultas tanpa mempertimbangkan besar_honor_FH
            $total_gaji_fakultas =
            $request->tj_tambahan +
            $request->honor_kinerja +
            $request->honor;

            // Mengecek apakah ada data besar_honor_FH dari tabel Honor_Fakultas_Tambahan
            $honor_fakultas_tambahan = Karyawan_Honor_Fakultas::where('karyawan_gaji_fakultas_id',$id)->whereNull('deleted_at')->get();
            if ($honor_fakultas_tambahan){
                foreach($honor_fakultas_tambahan as $honorfak){
                    // Jika ada, tambahkan besar_honor_FK ke total_gaji_fakultas
                $total_gaji_fakultas += $honorfak->besar_honor_FH;
            }
                }
           // Update Gaji Fakultas
            $gajifak -> update([
                'karyawan_id' => $request-> karyawan_id,
                'tj_tambahan' => $request-> tj_tambahan,
                'honor_kinerja' => $request-> honor_kinerja,
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
        $gajifak = Karyawan_Gaji_Fakultas::findOrFail($id);

        // Check if Data Gaji Fakultas exists
        if (!$gajifak) {
            throw new Exception('Data Gaji Fakultas Karyawan not found');
        }

        // Delete the related Honor_Fakultas_Tambahan records
        $gajifak->honorfakultastambahan()->delete();
        // Use Eloquent destroy to trigger cascading delete
        $gajifak->delete();

        return ResponseFormatter::success('Data Gaji Fakultas Karyawan deleted');

    } catch (Exception $e) {
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

}
