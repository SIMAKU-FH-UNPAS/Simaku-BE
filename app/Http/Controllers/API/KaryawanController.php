<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateKaryawanRequest;
use App\Http\Requests\UpdateKaryawanRequest;

class KaryawanController extends Controller
{
    public function fetch(Request $request ){
        $id = $request->input('id');
        $nama_karyawan = $request->input('nama_karyawan');
        $no_pegawai_karyawan = $request->input('no_pegawai_karyawan');
        $golongan_karyawan = $request->input('golongan_karyawan');
        $status_karyawan = $request->input('status_karyawan');
        $jabatan_karyawan = $request->input('jabatan_karyawan');
        $alamat_KTP_karyawan = $request->input('alamat_KTP_karyawan');
        $alamat_saatini_karyawan = $request->input('alamat_saatini_karyawan');
        $nama_bank_karyawan = $request->input('nama_bank_karyawan');
        $total_pendapatan_id = $request->input('total_pendapatan_id');
        $limit = $request->input('limit', 10);

        $karyawanQuery = Karyawan::query();


     // Get single data
    if($id)
    {
        $karyawan= $karyawanQuery->find($id);

        if($karyawan){
            return ResponseFormatter::success($karyawan, 'Data Karyawan found');
        }
            return ResponseFormatter::error('Data Karyawan not found', 404);
    }

    //    Get multiple Data
    $karyawan = $karyawanQuery;

    // Get by attribute
    if($karyawan)
    {
        $karyawan->where('nama_karyawan', 'like', '%'.$nama_karyawan.'%');

    }
    if($no_pegawai_karyawan)
    {
        $karyawan->where('no_pegawai_karyawan', 'like', '%'.$no_pegawai_karyawan.'%');

    }
    if($golongan_karyawan)
    {
        $karyawan->where('golongan_karyawan', 'like', '%'.$golongan_karyawan.'%');

    }
    if($status_karyawan)
    {
        $karyawan->where('status_karyawan', 'like', '%'.$status_karyawan.'%');

    }
    if($jabatan_karyawan)
    {
        $karyawan->where('jabatan_karyawan', 'like', '%'.$jabatan_karyawan.'%');

    }
    if($alamat_KTP_karyawan)
    {
        $karyawan->where('alamat_KTP_karyawan', 'like', '%'.$alamat_KTP_karyawan.'%');

    }
    if($alamat_saatini_karyawan)
    {
        $karyawan->where('alamat_saatini_karyawan', 'like', '%'.$alamat_saatini_karyawan.'%');

    }
    if($nama_bank_karyawan)
    {
        $karyawan->where('nama_bank_karyawan', 'like', '%'.$nama_bank_karyawan.'%');

    }
    if ($total_pendapatan_id) {
        $karyawan->where('total_pendapatan_id', $total_pendapatan_id);
    }

    return ResponseFormatter::success(
        $karyawan->paginate($limit),
        'Data Karyawan Found'
    );
    }

    public function create(CreateKaryawanRequest $request){
        try {

            // Create Dosen Luar Biasa
         $karyawan = Karyawan::create([
             'nama_karyawan' => $request-> nama_karyawan,
             'no_pegawai_karyawan' => $request-> no_pegawai_karyawan,
             'golongan_karyawan' => $request-> golongan_karyawan,
             'status_karyawan' => $request-> status_karyawan,
             'jabatan_karyawan' => $request-> jabatan_karyawan,
             'alamat_KTP_karyawan' => $request-> alamat_KTP_karyawan,
             'alamat_saatini_karyawan' => $request->  alamat_saatini_karyawan,
             'nama_bank_karyawan' => $request-> nama_bank_karyawan,
             'total_pendapatan_id' => $request-> total_pendapatan_id,

         ]);

         if(!$karyawan){
             throw new Exception('Data Karyawan not created');
         }
         return ResponseFormatter::success($karyawan, 'Data Karyawan created');
     }catch(Exception $e){
         return ResponseFormatter::error($e->getMessage(), 500);
     }
 }

 public function update(UpdateKaryawanRequest $request, $id)
 {
     try {

         // Get Dosen Luar Biasa
         $karyawan = Karyawan::find($id);

         // Check if Dosen Luar Biasa exists
         if(!$karyawan){
             throw new Exception('Data Karyawan not found');
         }

         // Update Dosen Luar Biasa
         $karyawan -> update([
         'nama_karyawan' => $request-> nama_karyawan,
         'no_pegawai_karyawan' => $request-> no_pegawai_karyawan,
         'golongan_karyawan' => $request-> golongan_karyawan,
         'status_karyawan' => $request-> status_karyawan,
         'jabatan_karyawan' => $request-> jabatan_karyawan,
         'alamat_KTP_karyawan' => $request-> alamat_KTP_karyawan,
         'alamat_saatini_karyawan' => $request->  alamat_saatini_karyawan,
         'nama_bank_karyawan' => $request-> nama_bank_karyawan,
         'total_pendapatan_id' => $request-> total_pendapatan_id,

     ]);


     return ResponseFormatter::success($karyawan, 'Data Karyawan updated');
 }catch(Exception $e){
     return ResponseFormatter::error($e->getMessage(), 500);
 }
 }

 public function destroy($id){
    try{
        // Get Data Dosen Luar Biasa
        $karyawan = Karyawan::find($id);

        // Check if Data Dosen Luar Biasa exists
        if(!$karyawan){
            throw new Exception('Data Karyawan not found');
        }

        // Delete Data Dosen Luar Biasa
        $karyawan->delete();

        return ResponseFormatter::success('Data Karyawan deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
