<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\dosenlb\CreateDosenLuarBiasaRequest;
use App\Http\Requests\dosenlb\UpdateDosenLuarBiasaRequest;

class DosenLuarBiasaController extends Controller
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

        $dosenlbQuery = Dosen_Luar_Biasa::query();


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
    if($nama)
    {
        $dosenlb->where('nama', 'like', '%'.$nama.'%');

    }
    if($no_pegawai)
    {
        $dosenlb->where('no_pegawai', 'like', '%'.$no_pegawai.'%');

    }
    if($status)
    {
        $dosenlb->where('status', 'like', '%'.$status.'%');

    }
    if($jabatan)
    {
        $dosenlb->where('jabatan', 'like', '%'.$jabatan.'%');

    }
    if($alamat_KTP)
    {
        $dosenlb->where('alamat_KTP', 'like', '%'.$alamat_KTP.'%');

    }
    if($alamat_saatini)
    {
        $dosenlb->where('alamat_saatini', 'like', '%'.$alamat_saatini.'%');

    }
    if($nama_bank)
    {
        $dosenlb->where('nama_bank', 'like', '%'.$nama_bank.'%');

    }
    if($norek_bank)
    {
        $dosenlb->where('norek_bank', 'like', '%'.$norek_bank.'%');

    }
    if ($golongan) {
        $dosenlb->where('golongan', 'like', '%'.$golongan.'%');
    }
    if($nomor_hp)
    {
        $dosenlb->where('nomor_hp', 'like', '%'.$norek_bank.'%');

    }


    return ResponseFormatter::success(
        $dosenlb->paginate($limit),
        'Data Dosen Luar Biasa Found'
    );
    }



    public function create(CreateDosenLuarBiasaRequest $request){
       try {

           // Create Dosen Luar Biasa
           $dosenlb = Dosen_Luar_Biasa::create([
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

        if(!$dosenlb){
            throw new Exception('Data Dosen Luar Biasa not created');
        }
        return ResponseFormatter::success($dosenlb, 'Data Dosen Luar Biasa created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

    public function update(UpdateDosenLuarBiasaRequest $request, $id)
    {
        try {

            // Get Dosen Luar Biasa
            $dosenlb = Dosen_Luar_Biasa::find($id);

            // Check if Dosen Luar Biasa exists
            if(!$dosenlb){
                throw new Exception('Data Dosen Luar Biasa not found');
            }

            // Update Dosen Luar Biasa
            $dosenlb -> update([
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


        return ResponseFormatter::success($dosenlb, 'Data Dosen Luar Biasa updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
    try{
        // Get Data Dosen Luar Biasa
        $dosenlb = Dosen_Luar_Biasa::find($id);

        // Check if Data Luar Biasa exists
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
