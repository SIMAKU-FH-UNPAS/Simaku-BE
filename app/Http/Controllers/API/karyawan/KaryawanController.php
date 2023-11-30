<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use Illuminate\Http\Request;
use App\Models\karyawan\Karyawan;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\karyawan\Karyawan_Bank;
use App\Http\Requests\karyawan\CreateBankRequest;
use App\Http\Requests\karyawan\UpdateBankRequest;
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
        $alamat_saat_ini = $request->input('alamat_saat_ini');
        $nomor_hp = $request->input('nomor_hp');
        $limit = $request->input('limit', 10);

        $karyawanQuery = Karyawan::query();


     // Get single data
    if($id)
    {
        $karyawan= $karyawanQuery->with('banks')->find($id);

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
    if($alamat_saat_ini)
    {
        $karyawan->where('alamat_saatini', 'like', '%'.$alamat_saat_ini.'%');

    }
    if ($golongan) {
        $karyawan->where('golongan', 'like', '%'.$golongan.'%');
    }
       if($nomor_hp)
    {
        $karyawan->where('nomor_hp', 'like', '%'.$nomor_hp.'%');

    }

 // Fetch Data ALL
 $karyawan->with('banks');
 return ResponseFormatter::success(
    $karyawan->paginate($limit),
    'Data Karyawan Found'
);
 return ResponseFormatter::success($karyawan, 'Data Karyawan Found');
}



    public function create(CreateKaryawanRequest $karyawanRequest, CreateBankRequest $bankRequest){
         // Memulai transaksi database
         DB::beginTransaction();
        try {

           // Create Karyawan
           $karyawan = Karyawan::create([
            'nama' => $karyawanRequest-> nama,
            'no_pegawai' => $karyawanRequest-> no_pegawai,
            'npwp' => $karyawanRequest-> npwp,
            'status' => $karyawanRequest-> status,
            'golongan' => $karyawanRequest-> golongan,
            'jabatan' => $karyawanRequest-> jabatan,
            'alamat_KTP' => $karyawanRequest-> alamat_KTP,
            'alamat_saat_ini' => $karyawanRequest-> alamat_saat_ini,
            'nomor_hp' => $karyawanRequest-> nomor_hp

        ]);

         // Create Data Bank
        //array bank
        $karyawanBanks = [];
        foreach ($bankRequest->input('banks') as $bank) {
           $karyawanBank = Karyawan_Bank::create([
                'nama_bank' => $bank['nama_bank'],
                'no_rekening' => $bank['no_rekening'],
                'karyawan_id' => $karyawan->id,
            ]);
            $karyawanBanks[] = $karyawanBank;
        }

         // Commit transaksi jika berhasil
         DB::commit();


        if(!$karyawan && !$karyawanBank){
            throw new Exception('Data Karyawan not created');
        }
        return ResponseFormatter::success(['karyawan' => $karyawan, 'banks' => $karyawanBanks], 'Data Karyawan created');
    }catch(Exception $e){
        DB::rollback();
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

    public function update(UpdateKaryawanRequest $karyawanRequest, UpdateBankRequest $bankRequest, $id)
    {
         // Memulai transaksi database
         DB::beginTransaction();
        try {

            // Get Karyawan
            $karyawan = Karyawan::find($id);

            // Check if Karyawan exists
            if(!$karyawan){
                return ResponseFormatter::error('Data Karyawan not found', 404);
            }

            // Update Karyawan
            $karyawan -> update([
                'nama' => $karyawanRequest-> nama,
                'no_pegawai' => $karyawanRequest-> no_pegawai,
                'npwp' => $karyawanRequest-> npwp,
                'status' => $karyawanRequest-> status,
                'golongan' => $karyawanRequest-> golongan,
                'jabatan' => $karyawanRequest-> jabatan,
                'alamat_KTP' => $karyawanRequest-> alamat_KTP,
                'alamat_saat_ini' => $karyawanRequest->  alamat_saat_ini,
                'nomor_hp' => $karyawanRequest-> nomor_hp

        ]);
           // Update Data Bank
           $karyawanBanks = [];
           foreach ($bankRequest->input('banks') as $bank) {
               // Ambil ID bank dari objek bank yang diambil dari database
                $karyawanBank = Karyawan_Bank::find($bank['id']);

               // Check if Bank exists
               if (!$karyawanBank) {
                   throw new Exception('Data Bank not found');
               }

               // Update data bank sesuai dengan ID
               $karyawanBank->update([
                   'nama_bank' => $bank['nama_bank'],
                   'no_rekening' => $bank['no_rekening'],
                   'karyawan_id' => $karyawanBank->karyawan_id
               ]);
               $karyawanBanks[] = $karyawanBank;
           }

            // Commit transaksi jika berhasil
            DB::commit();



        return ResponseFormatter::success(['karyawan' => $karyawan, 'banks' => $karyawanBanks], 'Data Karyawan updated');
    }catch(Exception $e){
        DB::rollback();
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
    try{
        // Get Data Karyawan
        $karyawan = Karyawan::find($id);

        // Check if Data Karyawan exists
        if(!$karyawan){
            return ResponseFormatter::error('Data Karyawan not found', 404);
        }
        // Delete the related records with Karyawan
        $karyawan->banks()->delete();

          // Delete the related records with karyawan
          $karyawan->master_transaksi()->each(function ($transaksi){
            // Delete related records
            $transaksi->gaji_universitas()->delete();
            $transaksi->gaji_fakultas()->delete();
            $transaksi->potongan()->delete();
            $transaksi->pajak()->delete();
            $transaksi->delete();
        });

        // Delete Data Karyawan
        $karyawan->delete();

        return ResponseFormatter::success('Data Karyawan deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
