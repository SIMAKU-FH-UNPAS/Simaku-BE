<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;


class PegawaiController extends Controller
{

    public function fetch(Request $request ){
        $id = $request->input('id');
        $nama = $request->input('nama');
        $no_pegawai = $request->input('no_pegawai');
        $status = $request->input('status');
        $posisi = $request->input('posisi');
        $golongan = $request->input('golongan');
        $jabatan = $request->input('jabatan');
        $alamat_KTP = $request->input('alamat_KTP');
        $alamat_saatini = $request->input('alamat_saatini');
        $nama_bank = $request->input('nama_bank');
        $norek_bank = $request->input('norek_bank');
        $limit = $request->input('limit', 10);

        $pegawaiQuery = Pegawai::query();


     // Get single data
    if($id)
    {
        $pegawai= $pegawaiQuery->find($id);

        if($pegawai){
            return ResponseFormatter::success($pegawai, 'Data Pegawai found');
        }
            return ResponseFormatter::error('Data Pegawai not found', 404);
    }

    //    Get multiple Data
    $pegawai = $pegawaiQuery;

    // Get by attribute
    if($nama)
    {
        $pegawai->where('nama', 'like', '%'.$nama.'%');

    }
    if($no_pegawai)
    {
        $pegawai->where('no_pegawai', 'like', '%'.$no_pegawai.'%');

    }
    if($posisi)
    {
        $pegawai->where('posisi', 'like', '%'.$posisi.'%');

    }
    if($status)
    {
        $pegawai->where('status', 'like', '%'.$status.'%');

    }
    if($jabatan)
    {
        $pegawai->where('jabatan', 'like', '%'.$jabatan.'%');

    }
    if($alamat_KTP)
    {
        $pegawai->where('alamat_KTP', 'like', '%'.$alamat_KTP.'%');

    }
    if($alamat_saatini)
    {
        $pegawai->where('alamat_saatini', 'like', '%'.$alamat_saatini.'%');

    }
    if($nama_bank)
    {
        $pegawai->where('nama_bank', 'like', '%'.$nama_bank.'%');

    }
    if($norek_bank)
    {
        $pegawai->where('norek_bank', 'like', '%'.$norek_bank.'%');

    }
    if ($golongan) {
        $pegawai->where('golongan', 'like', '%'.$golongan.'%');
    }


    return ResponseFormatter::success(
        $pegawai->paginate($limit),
        'Data Pegawai Found'
    );
    }



    public function create(CreatePegawaiRequest $request){
       try {

           // Create Dosen Luar Biasa
        $pegawai = Pegawai::create([
            'nama' => $request-> nama,
            'no_pegawai' => $request-> no_pegawai,
            'posisi' => $request-> posisi,
            'status' => $request-> status,
            'golongan' => $request-> golongan,
            'jabatan' => $request-> jabatan,
            'alamat_KTP' => $request-> alamat_KTP,
            'alamat_saatini' => $request->  alamat_saatini,
            'nama_bank' => $request-> nama_bank,
            'norek_bank' => $request-> norek_bank,

        ]);

        if(!$pegawai){
            throw new Exception('Data Pegawai not created');
        }
        return ResponseFormatter::success($pegawai, 'Data Pegawai created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

    public function update(UpdatePegawaiRequest $request, $id)
    {
        try {

            // Get Dosen Luar Biasa
            $pegawai = Pegawai::find($id);

            // Check if Dosen Luar Biasa exists
            if(!$pegawai){
                throw new Exception('Data Pegawai not found');
            }

            // Update Dosen Luar Biasa
            $pegawai -> update([
                'nama' => $request-> nama,
                'no_pegawai' => $request-> no_pegawai,
                'posisi' => $request-> posisi,
                'status' => $request-> status,
                'golongan' => $request-> golongan,
                'jabatan' => $request-> jabatan,
                'alamat_KTP' => $request-> alamat_KTP,
                'alamat_saatini' => $request->  alamat_saatini,
                'nama_bank' => $request-> nama_bank,
                'norek_bank' => $request-> norek_bank,

        ]);


        return ResponseFormatter::success($pegawai, 'Data Pegawai updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
    try{
        // Get Data Dosen Luar Biasa
        $pegawai = Pegawai::find($id);

        // Check if Data Dosen Luar Biasa exists
        if(!$pegawai){
            throw new Exception('Data Pegawai not found');
        }

        // Delete Data Dosen Luar Biasa
        $pegawai->delete();

        return ResponseFormatter::success('Data Pegawai deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
