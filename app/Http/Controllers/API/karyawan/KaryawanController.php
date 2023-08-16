<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use App\Models\karyawan\Karyawan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\karyawan\CreateKaryawanRequest;
use App\Http\Requests\karyawan\UpdateKaryawanRequest;

class KaryawanController extends Controller
{
    public function fetch(Request $request ){
        $id = $request->input('id');
        $nama = $request->input('nama');
        $no_pegawai = $request->input('no_pegawai');
        $status = $request->input('status');
        $golongan = $request->input('golongan');
        $jabatan = $request->input('jabatan');
        $alamat_KTP = $request->input('alamat_KTP');
        $alamat_saatini = $request->input('alamat_saatini');
        $nama_bank = $request->input('nama_bank');
        $norek_bank = $request->input('norek_bank');
        $nomor_hp = $request->input('nomor_hp');
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
    if($nama)
    {
        $karyawan->where('nama', 'like', '%'.$nama.'%');

    }
    if($no_pegawai)
    {
        $karyawan->where('no_pegawai', 'like', '%'.$no_pegawai.'%');

    }
    if($status)
    {
        $karyawan->where('status', 'like', '%'.$status.'%');

    }
    if($jabatan)
    {
        $karyawan->where('jabatan', 'like', '%'.$jabatan.'%');

    }
    if($alamat_KTP)
    {
        $karyawan->where('alamat_KTP', 'like', '%'.$alamat_KTP.'%');

    }
    if($alamat_saatini)
    {
        $karyawan->where('alamat_saatini', 'like', '%'.$alamat_saatini.'%');

    }
    if($nama_bank)
    {
        $karyawan->where('nama_bank', 'like', '%'.$nama_bank.'%');

    }
    if($norek_bank)
    {
        $karyawan->where('norek_bank', 'like', '%'.$norek_bank.'%');

    }
    if ($golongan) {
        $karyawan->where('golongan', 'like', '%'.$golongan.'%');
    }
       if($nomor_hp)
    {
        $karyawan->where('nomor_hp', 'like', '%'.$norek_bank.'%');

    }


    return ResponseFormatter::success(
        $karyawan->paginate($limit),
        'Data Karyawan Found'
    );
    }



    public function create(CreateKaryawanRequest $request){
       try {

           // Create Karyawan
           $karyawan = Karyawan::create([
            'nama' => $request-> nama,
            'no_pegawai' => $request-> no_pegawai,
            'status' => $request-> status,
            'golongan' => $request-> golongan,
            'jabatan' => $request-> jabatan,
            'alamat_KTP' => $request-> alamat_KTP,
            'alamat_saatini' => $request->  alamat_saatini,
            'nama_bank' => $request-> nama_bank,
            'norek_bank' => $request-> norek_bank,
            'nomor_hp' => $request-> nomor_hp

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

            // Get Karyawan
            $karyawan = Karyawan::find($id);

            // Check if Karyawan exists
            if(!$karyawan){
                throw new Exception('Data Karyawan not found');
            }

            // Update Karyawan
            $karyawan -> update([
                'nama' => $request-> nama,
                'no_pegawai' => $request-> no_pegawai,
                'status' => $request-> status,
                'golongan' => $request-> golongan,
                'jabatan' => $request-> jabatan,
                'alamat_KTP' => $request-> alamat_KTP,
                'alamat_saatini' => $request->  alamat_saatini,
                'nama_bank' => $request-> nama_bank,
                'norek_bank' => $request-> norek_bank,
                'nomor_hp' => $request-> nomor_hp

        ]);


        return ResponseFormatter::success($karyawan, 'Data Karyawan updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
    try{
        // Get Data Karyawan
        $karyawan = Karyawan::find($id);

        // Check if Data Karyawan exists
        if(!$karyawan){
            throw new Exception('Data Karyawan not found');
        }

        // Delete Data Karyawan
        $karyawan->delete();

        return ResponseFormatter::success('Data Karyawan deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
