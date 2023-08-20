<?php

namespace App\Http\Controllers\API\dosentetap;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\dosentetap\Dosen_Tetap;
use App\Models\dosentetap\Dostap_Honor_Fakultas;
use App\Models\dosentetap\Dostap_Potongan_Tambahan;
use App\Http\Requests\dosentetap\CreateDosenTetapRequest;
use App\Http\Requests\dosentetap\UpdateDosenTetapRequest;

class DosenTetapController extends Controller
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
    if($nama)
    {
        $dosentetap->where('nama', 'like', '%'.$nama.'%');

    }
    if($no_pegawai)
    {
        $dosentetap->where('no_pegawai', 'like', '%'.$no_pegawai.'%');

    }
    if($status)
    {
        $dosentetap->where('status', 'like', '%'.$status.'%');

    }
    if($jabatan)
    {
        $dosentetap->where('jabatan', 'like', '%'.$jabatan.'%');

    }
    if($alamat_KTP)
    {
        $dosentetap->where('alamat_KTP', 'like', '%'.$alamat_KTP.'%');

    }
    if($alamat_saatini)
    {
        $dosentetap->where('alamat_saatini', 'like', '%'.$alamat_saatini.'%');

    }
    if($nama_bank)
    {
        $dosentetap->where('nama_bank', 'like', '%'.$nama_bank.'%');

    }
    if($norek_bank)
    {
        $dosentetap->where('norek_bank', 'like', '%'.$norek_bank.'%');

    }
    if ($golongan) {
        $dosentetap->where('golongan', 'like', '%'.$golongan.'%');
    }
    if($nomor_hp)
    {
        $dosentetap->where('nomor_hp', 'like', '%'.$norek_bank.'%');

    }


    return ResponseFormatter::success(
        $dosentetap->paginate($limit),
        'Data Dosen Tetap Found'
    );
    }



    public function create(CreateDosenTetapRequest $request){
       try {

           // Create Dosen Tetap
           $dosentetap = Dosen_Tetap::create([
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

            // Get Dosen Tetap
            $dosentetap = Dosen_Tetap::find($id);

            // Check if Dosen Tetap exists
            if(!$dosentetap){
                throw new Exception('Data Dosen Tetap not found');
            }

            // Update Dosen Tetap
            $dosentetap -> update([
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


        return ResponseFormatter::success($dosentetap, 'Data Dosen Tetap updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
    try{
        DB::beginTransaction();
        // Get Data Dosen Tetap
        $dosentetap = Dosen_Tetap::find($id);

        // Check if Data Tetap exists
        if(!$dosentetap){
            throw new Exception('Data Dosen Tetap not found');
        }
         // Delete the related records with Dosen Tetap
         $dosentetap->gaji_universitas()->delete();
         $dosentetap->gaji_fakultas->each(function ($gajiFakultas) {
            // Delete related Dostap_Honor_Fakultas records
            $gajiFakultas->honorfakultastambahan()->delete();
            $gajiFakultas->delete();
        });
        $dosentetap->potongan->each(function ($potongan) {
            // Delete related Dostap_Potongan records
            $potongan->potongantambahan()->delete();
            $potongan->delete();
        });
        $dosentetap->pajak()->delete();
        // Delete Data Dosen Tetap
        $dosentetap->delete();

        DB::commit();

        return ResponseFormatter::success('Data Dosen Tetap deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}


