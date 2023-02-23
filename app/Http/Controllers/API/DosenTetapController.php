<?php

namespace App\Http\Controllers\API;

use App\Models\Dosen_Tetap;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDosenTetapRequest;
use App\Http\Requests\UpdateDosenTetapRequest;
use Exception;

class DosenTetapController extends Controller
{
    public function fetch(Request $request ){
        $id = $request->input('id');
        $nama_dostap = $request->input('nama_dostap');
        $no_pegawai_dostap = $request->input('no_pegawai_dostap');
        $golongan_dostap = $request->input('golongan_dostap');
        $status_dostap = $request->input('status_dostap');
        $jabatan_dostap = $request->input('jabatan_dostap');
        $alamat_KTP_dostap = $request->input('alamat_KTP_dostap');
        $alamat_saatini_dostap = $request->input('alamat_saatini_dostap');
        $nama_bank_dostap = $request->input('nama_bank_dostap');
        $total_pendapatan_id = $request->input('total_pendapatan_id');
        $limit = $request->input('limit', 10);

        $dosentetapQuery = Dosen_Tetap::query();


     // Get single data
    if($id)
    {
        $dosentetap= $dosentetapQuery->find($id);

        if($dosentetap){
            return ResponseFormatter::success($dosentetap, 'Data Dosen Tetap found');
        }
            return ResponseFormatter::error('Data Dosen Tetap not found', 404);
    }

    //    Get multiple Data
    $dosentetap = $dosentetapQuery;

    // Get by attribute
    if($nama_dostap)
    {
        $dosentetap->where('nama_dostap', 'like', '%'.$nama_dostap.'%');

    }
    if($no_pegawai_dostap)
    {
        $dosentetap->where('no_pegawai_dostap', 'like', '%'.$no_pegawai_dostap.'%');

    }
    if($golongan_dostap)
    {
        $dosentetap->where('golongan_dostap', 'like', '%'.$golongan_dostap.'%');

    }
    if($status_dostap)
    {
        $dosentetap->where('status_dostap', 'like', '%'.$status_dostap.'%');

    }
    if($jabatan_dostap)
    {
        $dosentetap->where('jabatan_dostap', 'like', '%'.$jabatan_dostap.'%');

    }
    if($alamat_KTP_dostap)
    {
        $dosentetap->where('alamat_KTP_dostap', 'like', '%'.$alamat_KTP_dostap.'%');

    }
    if($alamat_saatini_dostap)
    {
        $dosentetap->where('alamat_saatini_dostap', 'like', '%'.$alamat_saatini_dostap.'%');

    }
    if($nama_bank_dostap)
    {
        $dosentetap->where('nama_bank_dostap', 'like', '%'.$nama_bank_dostap.'%');

    }
    if ($total_pendapatan_id) {
        $dosentetap->where('total_pendapatan_id', $total_pendapatan_id);
    }

    return ResponseFormatter::success(
        $dosentetap->paginate($limit),
        'Data Dosen Tetap Found'
    );
    }

    public function create(CreateDosenTetapRequest $request){
        try {

            // Create Dosen Luar Biasa
         $dosentetap = Dosen_Tetap::create([
             'nama_dostap' => $request-> nama_dostap,
             'no_pegawai_dostap' => $request-> no_pegawai_dostap,
             'golongan_dostap' => $request-> golongan_dostap,
             'status_dostap' => $request-> status_dostap,
             'jabatan_dostap' => $request-> jabatan_dostap,
             'alamat_KTP_dostap' => $request-> alamat_KTP_dostap,
             'alamat_saatini_dostap' => $request->  alamat_saatini_dostap,
             'nama_bank_dostap' => $request-> nama_bank_dostap,
             'total_pendapatan_id' => $request-> total_pendapatan_id,

         ]);

         if(!$dosentetap){
             throw new Exception('Data Dosen Tetap not created');
         }
         return ResponseFormatter::success($dosentetap, 'Data Dosen Tetap created');
     }catch(Exception $e){
         return ResponseFormatter::error($e->getMessage(), 500);
     }
 }

 public function update(UpdateDosenTetapRequest $request, $id)
 {
     try {

         // Get Dosen Luar Biasa
         $dosentetap = Dosen_Tetap::find($id);

         // Check if Dosen Luar Biasa exists
         if(!$dosentetap){
             throw new Exception('Data Dosen Tetap not found');
         }

         // Update Dosen Luar Biasa
         $dosentetap -> update([
         'nama_dostap' => $request-> nama_dostap,
         'no_pegawai_dostap' => $request-> no_pegawai_dostap,
         'golongan_dostap' => $request-> golongan_dostap,
         'status_dostap' => $request-> status_dostap,
         'jabatan_dostap' => $request-> jabatan_dostap,
         'alamat_KTP_dostap' => $request-> alamat_KTP_dostap,
         'alamat_saatini_dostap' => $request->  alamat_saatini_dostap,
         'nama_bank_dostap' => $request-> nama_bank_dostap,
         'total_pendapatan_id' => $request-> total_pendapatan_id,

     ]);


     return ResponseFormatter::success($dosentetap, 'Data Dosen Tetap updated');
 }catch(Exception $e){
     return ResponseFormatter::error($e->getMessage(), 500);
 }
 }

 public function destroy($id){
    try{
        // Get Data Dosen Luar Biasa
        $dosentetap = Dosen_Tetap::find($id);

        // Check if Data Dosen Luar Biasa exists
        if(!$dosentetap){
            throw new Exception('Data Dosen Tetap not found');
        }

        // Delete Data Dosen Luar Biasa
        $dosentetap->delete();

        return ResponseFormatter::success('Data Dosen Tetap deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
