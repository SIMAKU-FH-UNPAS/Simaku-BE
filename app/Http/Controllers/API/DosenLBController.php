<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Dosen_LuarBiasa;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDosenLBRequest;
use App\Http\Requests\UpdateDosenLBRequest;
use Exception;


class DosenLBController extends Controller
{

    public function fetch(Request $request ){
        $id = $request->input('id');
        $nama_dosluar = $request->input('nama_dosluar');
        $no_pegawai_dosluar = $request->input('no_pegawai_dosluar');
        $golongan_dosluar = $request->input('golongan_dosluar');
        $status_dosluar = $request->input('status_dosluar');
        $jabatan_dosluar = $request->input('jabatan_dosluar');
        $alamat_KTP_dosluar = $request->input('alamat_KTP_dosluar');
        $alamat_saatini_dosluar = $request->input('alamat_saatini_dosluar');
        $nama_bank_dosluar = $request->input('nama_bank_dosluar');
        $total_pendapatan_id = $request->input('total_pendapatan_id');
        $limit = $request->input('limit', 10);

        $dosenlbQuery = Dosen_LuarBiasa::query();


     // Get single data
    if($id)
    {
        $dosenlb= $dosenlbQuery->find($id);

        if($dosenlb){
            return ResponseFormatter::success($dosenlb, 'Data Dosen Luar Biasa found');
        }
            return ResponseFormatter::error('Data Dosen Luar Biasa not found', 404);
    }

    //    Get multiple Data
    $dosenlb = $dosenlbQuery;

    // Get by attribute
    if($nama_dosluar)
    {
        $dosenlb->where('nama_dosluar', 'like', '%'.$nama_dosluar.'%');

    }
    if($no_pegawai_dosluar)
    {
        $dosenlb->where('no_pegawai_dosluar', 'like', '%'.$no_pegawai_dosluar.'%');

    }
    if($golongan_dosluar)
    {
        $dosenlb->where('golongan_dosluar', 'like', '%'.$golongan_dosluar.'%');

    }
    if($status_dosluar)
    {
        $dosenlb->where('status_dosluar', 'like', '%'.$status_dosluar.'%');

    }
    if($jabatan_dosluar)
    {
        $dosenlb->where('jabatan_dosluar', 'like', '%'.$jabatan_dosluar.'%');

    }
    if($alamat_KTP_dosluar)
    {
        $dosenlb->where('alamat_KTP_dosluar', 'like', '%'.$alamat_KTP_dosluar.'%');

    }
    if($alamat_saatini_dosluar)
    {
        $dosenlb->where('alamat_saatini_dosluar', 'like', '%'.$alamat_saatini_dosluar.'%');

    }
    if($nama_bank_dosluar)
    {
        $dosenlb->where('nama_bank_dosluar', 'like', '%'.$nama_bank_dosluar.'%');

    }
    if ($total_pendapatan_id) {
        $dosenlb->where('total_pendapatan_id', $total_pendapatan_id);
    }

    return ResponseFormatter::success(
        $dosenlb->paginate($limit),
        'Data Dosen Luar Biasa Found'
    );
    }



    public function create(CreateDosenLBRequest $request){
       try {

           // Create Dosen Luar Biasa
        $dosenlb = Dosen_LuarBiasa::create([
            'nama_dosluar' => $request-> nama_dosluar,
            'no_pegawai_dosluar' => $request-> no_pegawai_dosluar,
            'golongan_dosluar' => $request-> golongan_dosluar,
            'status_dosluar' => $request-> status_dosluar,
            'jabatan_dosluar' => $request-> jabatan_dosluar,
            'alamat_KTP_dosluar' => $request-> alamat_KTP_dosluar,
            'alamat_saatini_dosluar' => $request->  alamat_saatini_dosluar,
            'nama_bank_dosluar' => $request-> nama_bank_dosluar,
            'total_pendapatan_id' => $request-> total_pendapatan_id,

        ]);

        if(!$dosenlb){
            throw new Exception('Data Dosen Luar Biasa not created');
        }
        return ResponseFormatter::success($dosenlb, 'Data Dosen Luar Biasa created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

    public function update(UpdateDosenLBRequest $request, $id)
    {
        try {

            // Get Dosen Luar Biasa
            $dosenlb = Dosen_LuarBiasa::find($id);

            // Check if Dosen Luar Biasa exists
            if(!$dosenlb){
                throw new Exception('Data Dosen Luar Biasa not found');
            }

            // Update Dosen Luar Biasa
            $dosenlb -> update([
            'nama_dosluar' => $request-> nama_dosluar,
            'no_pegawai_dosluar' => $request-> no_pegawai_dosluar,
            'golongan_dosluar' => $request-> golongan_dosluar,
            'status_dosluar' => $request-> status_dosluar,
            'jabatan_dosluar' => $request-> jabatan_dosluar,
            'alamat_KTP_dosluar' => $request-> alamat_KTP_dosluar,
            'alamat_saatini_dosluar' => $request->  alamat_saatini_dosluar,
            'nama_bank_dosluar' => $request-> nama_bank_dosluar,
            'total_pendapatan_id' => $request-> total_pendapatan_id,

        ]);


        return ResponseFormatter::success($dosenlb, 'Data Dosen Luar Biasa updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
    try{
        // Get Data Dosen Luar Biasa
        $dosenlb = Dosen_LuarBiasa::find($id);

        // Check if Data Dosen Luar Biasa exists
        if(!$dosenlb){
            throw new Exception('Data Dosen Luar Biasa not found');
        }

        // Delete Data Dosen Luar Biasa
        $dosenlb->delete();

        return ResponseFormatter::success('Data Dosen Luar Biasa deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
