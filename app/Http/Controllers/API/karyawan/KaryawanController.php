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

        $karyawanQuery = Karyawan::query();


     // Get single data
    if($id)
    {
        $karyawan= $karyawanQuery->find($id);

        if($karyawan){
            // Modifikasi Array bank
            $karyawan->bank = [
                "nama_bank_utama" => $karyawan->nama_bank_utama,
                "norek_bank_utama" => $karyawan->norek_bank_utama,
                "nama_bank_tambahan" => $karyawan->nama_bank_tambahan,
                "norek_tambahan" => $karyawan->norek_tambahan,
            ];
            // Menghapus field array
            unset($karyawan->nama_bank_utama, $karyawan->norek_bank_utama, $karyawan->nama_bank_tambahan, $karyawan->norek_tambahan);
            unset($karyawan->nama_bank_utama, $karyawan->norek_bank_utama, $karyawan->nama_bank_tambahan, $karyawan->norek_bank_tambahan);
            $deletedAt = $karyawan->deleted_at;
            $createdAt = $karyawan->created_at;
            $updatedAt = $karyawan->updated_at;
            unset($karyawan->deleted_at, $karyawan->created_at, $karyawan->updated_at);

            // Mengganti posisi field array
            $karyawan->deleted_at = $deletedAt;
            $karyawan->created_at = $createdAt;
            $karyawan->updated_at = $updatedAt;

            return ResponseFormatter::success([$karyawan], 'Data Karyawan found');
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
    if($npwp)
    {
        $karyawan->where('npwp', 'like', '%'.$npwp.'%');

    }
    if($status)
    {
             // Filter berdasarkan status "aktif" atau "tidak aktif"
    if ($status === 'Aktif' || $status === 'Tidak Aktif') {
        $karyawan->where('status', $status);
    } else {
        // Jika nilai status tidak valid, berikan respon error
        return ResponseFormatter::error('Data Karyawan not Found', 400);
    }


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
    if($nama_bank_utama)
    {
        $karyawan->where('nama_bank_utama', 'like', '%'.$nama_bank_utama.'%');

    }
    if($norek_bank_utama)
    {
        $karyawan->where('norek_bank_utama', 'like', '%'.$norek_bank_utama.'%');

    }
    if($nama_bank_tambahan)
    {
        $karyawan->where('nama_bank_tambahan', 'like', '%'.$nama_bank_tambahan.'%');

    }
    if($norek_bank_tambahan)
    {
        $karyawan->where('norek_bank_tambahan', 'like', '%'.$norek_bank_tambahan.'%');

    }
    if ($golongan) {
        $karyawan->where('golongan', 'like', '%'.$golongan.'%');
    }
       if($nomor_hp)
    {
        $karyawan->where('nomor_hp', 'like', '%'.$nomor_hp.'%');

    }

 // Fetch Data ALL
 $karyawanData = $karyawan->paginate($limit);
 $ArrayKaryawan = [];

 foreach ($karyawanData as $karyawan) {
     $data = [
         'id' => $karyawan->id,
         'nama' => $karyawan->nama,
         'no_pegawai' => $karyawan->no_pegawai,
         'npwp' => $karyawan->npwp,
         'status' => $karyawan->status,
         'golongan' => $karyawan->golongan,
         'jabatan' => $karyawan->jabatan,
         'alamat_KTP' => $karyawan->alamat_KTP,
         'alamat_saatini' => $karyawan->alamat_saatini,
         'nomor_hp' => $karyawan->nomor_hp,
         'bank' => [
             'nama_bank_utama' => $karyawan->nama_bank_utama,
             'norek_bank_utama' => $karyawan->norek_bank_utama,
             'nama_bank_tambahan' => $karyawan->nama_bank_tambahan,
             'norek_bank_tambahan' => $karyawan->norek_bank_tambahan,
         ],
         'deleted_at' => $karyawan->deleted_at,
         'created_at' => $karyawan->created_at,
         'updated_at' => $karyawan->updated_at,
     ];

     $ArrayKaryawan[] = $data;
 }

 return ResponseFormatter::success($ArrayKaryawan, 'Data Karyawan Found');
}



    public function create(CreateKaryawanRequest $request){
       try {

           // Create Karyawan
           $karyawan = Karyawan::create([
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
        // Delete the related records with Karyawan
        $karyawan->gaji_universitas()->delete();
        $karyawan->gaji_fakultas()->each(function ($gajiFakultas) {
            // Delete related Karyawan_Honor_Fakultas records
            $gajiFakultas->honorfakultastambahan()->delete();
            $gajiFakultas->delete();
        });
        $karyawan->potongan()->each(function ($potongan) {
            // Delete related Karyawan_Potongan records
            $potongan->potongantambahan()->delete();
            $potongan->delete();
        });
        $karyawan->pajak()->delete();
        // Delete Data Karyawan
        $karyawan->delete();

        return ResponseFormatter::success('Data Karyawan deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
