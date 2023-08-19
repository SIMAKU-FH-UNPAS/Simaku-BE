<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use App\Models\dosenlb\Doslb_Pajak;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\dosenlb\CreatePajakRequest;
use App\Http\Requests\dosenlb\UpdatePajakRequest;

class PajakController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $pajak_pph25 = $request->input('pajak_pph25');
        $pendapatan_bersih= $request->input('pendapatan_bersih');
        $dosen_luar_biasa_id= $request->input('dosen_luar_biasa_id');
        $doslb_pendapatan_id= $request->input('doslb_pendapatan_id');
        $doslb_potongan_id = $request->input('doslb_potongan_id');
        $limit = $request->input('limit', 10);

        $PajakQuery = Doslb_Pajak::query();

    // Get single data
    if($id)
    {
        $pajak=  $PajakQuery->find($id);

        if($pajak){
            return ResponseFormatter::success($pajak, 'Data Pajak Dosen Luar Biasa found');
        }
            return ResponseFormatter::error('Data Pajak Dosen Luar Biasa not found', 404);
    }

    //    Get multiple Data
    $pajak = $PajakQuery;

    // // Get by attribute
    if($pajak_pph25)
    {
        $pajak->where('pajak_pph25', 'like', '%'.$pajak_pph25.'%');

    }
    if($pendapatan_bersih)
    {
        $pajak->where('pendapatan_bersih', 'like', '%'.$pendapatan_bersih.'%');

    }
    if ($dosen_luar_biasa_id) {
        $pajak->where('dosen_luar_biasa_id', $dosen_luar_biasa_id);
    }
    if ($doslb_pendapatan_id) {
        $pajak->where('doslb_pendapatan_id', $doslb_pendapatan_id);
    }
    if ($doslb_potongan_id) {
        $pajak->where('doslb_potongan_id', $doslb_potongan_id);
    }



    return ResponseFormatter::success(
        $pajak->paginate($limit),
        'Data Pajak Dosen Luar Biasa Found'
    );
}
public function create(CreatePajakRequest $request){
    try{
        $dosen_luar_biasa_id = $request->dosen_luar_biasa_id;

        // Get the current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;


        // Check if data already exists for the current month and year
        $existingpajak= Doslb_Pajak::where('dosen_luar_biasa_id', $dosen_luar_biasa_id)
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->first();

    if ($existingpajak) {
        return ResponseFormatter::error('Data Pajak Dosen Luar Biasa for this month already exists', 400);
    }
        // Get Data Pegawai
        $dosen_luar_biasa = Dosen_Luar_Biasa::findOrFail($request->dosen_luar_biasa_id);

        if ($dosen_luar_biasa->komponen_pendapatan->isNotEmpty() && $dosen_luar_biasa->potongan->isNotEmpty()){
        // Rumus Perhitungan menggunakan metode pada model Pajak
        $pajak = new Doslb_Pajak;
        $pajak_pph25 = $pajak->hitung_pajak_pph25($request);
        $pendapatan_bersih = $pajak->hitung_pendapatan_bersih($request);

        // Create Pajak Pegawai
        $pajakdosenluarbiasa = Doslb_Pajak::create([
            'pajak_pph25' => $pajak_pph25,
            'pendapatan_bersih' => $pendapatan_bersih,
            'dosen_luar_biasa_id' => $dosen_luar_biasa->id,
            'doslb_pendapatan_id'=>$request->doslb_pendapatan_id,
            'doslb_potongan_id'=>$request->doslb_potongan_id
        ]);
        return ResponseFormatter::success($pajakdosenluarbiasa, 'Data Pajak Dosen Luar Biasa created');
    }else{
         return ResponseFormatter::error('Gaji Universitas or Gaji Fakultas or Potongan is empty ');
    }
    throw new Exception('Data Pajak Dosen Luar Biasa not created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

    public function update(UpdatePajakRequest $request, $id)
    {
        try {

            // Get Pajak Pegawai
            $pajakdosenluarbiasa = Doslb_Pajak::find($id);

            // Check if Pajak Pegawai exists
            if(!$pajakdosenluarbiasa){
                throw new Exception('Data Pajak Dosen Luar Biasa not found');
            }
        // Rumus Perhitungan menggunakan metode pada model Pajak
        $pajak = new Doslb_Pajak;
        $pajak_pph25 = $pajak->hitung_pajak_pph25($request);
        $pendapatan_bersih = $pajak->hitung_pendapatan_bersih($request);


            // Update Pajak Pegawai
            $pajakdosenluarbiasa -> update([
            'pajak_pph25' => $pajak_pph25,
            'pendapatan_bersih' => $pendapatan_bersih,
            'dosen_luar_biasa_id' => $request->dosen_luar_biasa_id,
            'doslb_pendapatan_id'=>$request->doslb_pendapatan_id,
            'doslb_potongan_id'=>$request->doslb_potongan_id

        ]);


        return ResponseFormatter::success($pajakdosenluarbiasa, 'Data Pajak Dosen Luar Biasa updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }
    public function destroy($id){
        try{
            // Get Data Pajak Pegawai
            $pajakdosenluarbiasa = Doslb_Pajak::findorFail($id);

            // Check if Data Pajak Pegawai exists
            if(!$pajakdosenluarbiasa){
                throw new Exception('Data Pajak Dosen Luar Biasa not found');
            }

            // Delete the related Pajak_Tambahan records
            $pajakdosenluarbiasa->pajaktambahan()->delete();
            // Delete Data Pajak Pegawai
            $pajakdosenluarbiasa->delete();

            return ResponseFormatter::success('Data Pajak Dosen Luar Biasa deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
