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
        $npwp = $request->input('npwp');
        $status = $request->input('status');
        $golongan = $request->input('golongan');
        $jabatan = $request->input('jabatan');
        $alamat_KTP = $request->input('alamat_KTP');
        $alamat_saatini = $request->input('alamat_saatini');
        $nama_bank_utama = $request->input('nama_bank_utama');
        $nama_bank_tambahan = $request->input('nama_bank_tambahan');
        $norek_bank_utama = $request->input('norek_bank_utama');
        $norek_bank_tambahan = $request->input('norek_bank_tambahan');
        $nomor_hp = $request->input('nomor_hp');
        $limit = $request->input('limit', 10);

        $dosentetapQuery = Dosen_Tetap::query();


    // Get single data
    if ($id) {
        $dosentetap = $dosentetapQuery->find($id);

        if ($dosentetap) {
            // Modifikasi Array bank
            $dosentetap->bank = [
                "nama_bank_utama" => $dosentetap->nama_bank_utama,
                "norek_bank_utama" => $dosentetap->norek_bank_utama,
                "nama_bank_tambahan" => $dosentetap->nama_bank_tambahan,
                "norek_bank_tambahan" => $dosentetap->norek_bank_tambahan,
            ];
            // Menghapus field array
            unset($dosentetap->nama_bank_utama, $dosentetap->norek_bank_utama, $dosentetap->nama_bank_tambahan, $dosentetap->norek_bank_tambahan);
            $deletedAt = $dosentetap->deleted_at;
            $createdAt = $dosentetap->created_at;
            $updatedAt = $dosentetap->updated_at;
            unset($dosentetap->deleted_at, $dosentetap->created_at, $dosentetap->updated_at);

            // Mengganti posisi field array
            $dosentetap->deleted_at = $deletedAt;
            $dosentetap->created_at = $createdAt;
            $dosentetap->updated_at = $updatedAt;


            return ResponseFormatter::success([$dosentetap], 'Data Dosen Tetap found');
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
    if($npwp)
    {
        $dosentetap->where('npwp', 'like', '%'.$npwp.'%');

    }
    if($status)
    {
        // Filter berdasarkan status "aktif" atau "tidak aktif"
    if ($status === 'Aktif' || $status === 'Tidak Aktif') {
        $dosentetap->where('status', $status);
    } else {
        // Jika nilai status tidak valid, berikan respon error
        return ResponseFormatter::error('Data Dosen Tetap not Found', 400);
    }

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
    if($nama_bank_utama)
    {
        $dosentetap->where('nama_bank_utama', 'like', '%'.$nama_bank_utama.'%');

    }
    if($norek_bank_utama)
    {
        $dosentetap->where('norek_bank_utama', 'like', '%'.$norek_bank_utama.'%');

    }
    if($nama_bank_tambahan)
    {
        $dosentetap->where('nama_bank_tambahan', 'like', '%'.$nama_bank_tambahan.'%');

    }
    if($norek_bank_tambahan)
    {
        $dosentetap->where('norek_bank_tambahan', 'like', '%'.$norek_bank_tambahan.'%');

    }
    if ($golongan) {
        $dosentetap->where('golongan', 'like', '%'.$golongan.'%');
    }
    if($nomor_hp)
    {
        $dosentetap->where('nomor_hp', 'like', '%'.$nomor_hp.'%');

    }

 // Fetch Data ALL
 $dosentetapData = $dosentetap->paginate($limit);
 $ArrayDosenTetap = [];

 foreach ($dosentetapData as $dosentetap) {
     $data = [
         'id' => $dosentetap->id,
         'nama' => $dosentetap->nama,
         'no_pegawai' => $dosentetap->no_pegawai,
         'npwp' => $dosentetap->npwp,
         'status' => $dosentetap->status,
         'golongan' => $dosentetap->golongan,
         'jabatan' => $dosentetap->jabatan,
         'alamat_KTP' => $dosentetap->alamat_KTP,
         'alamat_saatini' => $dosentetap->alamat_saatini,
         'nomor_hp' => $dosentetap->nomor_hp,
         'bank' => [
             'nama_bank_utama' => $dosentetap->nama_bank_utama,
             'norek_bank_utama' => $dosentetap->norek_bank_utama,
             'nama_bank_tambahan' => $dosentetap->nama_bank_tambahan,
             'norek_bank_tambahan' => $dosentetap->norek_bank_tambahan,
         ],
         'deleted_at' => $dosentetap->deleted_at,
         'created_at' => $dosentetap->created_at,
         'updated_at' => $dosentetap->updated_at,
     ];

     $ArrayDosenTetap[] = $data;
 }

 return ResponseFormatter::success($ArrayDosenTetap, 'Data Dosen Tetap Found');
}



    public function create(CreateDosenTetapRequest $request){
       try {

           // Create Dosen Tetap
           $dosentetap = Dosen_Tetap::create([
            'nama' => $request-> nama,
            'no_pegawai' => $request-> no_pegawai,
            'npwp' => $request-> npwp,
            'status' => $request-> status,
            'golongan' => $request-> golongan,
            'jabatan' => $request-> jabatan,
            'alamat_KTP' => $request-> alamat_KTP,
            'alamat_saatini' => $request->  alamat_saatini,
            'nama_bank_utama' => $request-> nama_bank_utama,
            'norek_bank_utama' => $request-> norek_bank_utama,
            'nama_bank_tambahan' => $request-> nama_bank_tambahan,
            'norek_bank_tambahan' => $request-> norek_bank_tambahan,
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
                'npwp' => $request-> npwp,
                'status' => $request-> status,
                'golongan' => $request-> golongan,
                'jabatan' => $request-> jabatan,
                'alamat_KTP' => $request-> alamat_KTP,
                'alamat_saatini' => $request->  alamat_saatini,
                'nama_bank_utama' => $request-> nama_bank_utama,
                'norek_bank_utama' => $request-> norek_bank_utama,
                'nama_bank_tambahan' => $request-> nama_bank_tambahan,
                'norek_bank_tambahan' => $request-> norek_bank_tambahan,
                'nomor_hp' => $request-> nomor_hp

        ]);


        return ResponseFormatter::success($dosentetap, 'Data Dosen Tetap updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
    try{
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


        return ResponseFormatter::success('Data Dosen Tetap deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}


