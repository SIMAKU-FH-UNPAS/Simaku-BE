<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\dosenlb\Doslb_Bank;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\dosenlb\Dosen_Luar_Biasa;
use App\Http\Requests\dosenlb\CreateBankRequest;
use App\Http\Requests\dosenlb\UpdateBankRequest;
use App\Http\Requests\dosenlb\CreateDosenLuarBiasaRequest;
use App\Http\Requests\dosenlb\UpdateDosenLuarBiasaRequest;

class DosenLuarBiasaController extends Controller
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
        $nama_bank_utama = $request->input('nama_bank_utama');
        $nama_bank_tambahan = $request->input('nama_bank_tambahan');
        $norek_bank_utama = $request->input('norek_bank_utama');
        $norek_bank_tambahan = $request->input('norek_bank_tambahan');
        $nomor_hp = $request->input('nomor_hp');
        $limit = $request->input('limit', 10);

        $dosenlbQuery = Dosen_Luar_Biasa::query();


     // Get single data
    if($id)
    {
        $dosenlb= $dosenlbQuery->with('banks')->find($id);

        if($dosenlb){
            return ResponseFormatter::success([$dosenlb], 'Data Dosen Luar Biasa found');
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
    if($npwp)
    {
        $dosenlb->where('npwp', 'like', '%'.$npwp.'%');

    }
    if($status)
    {
         // Filter berdasarkan status "aktif" atau "tidak aktif"
    if ($status === 'Aktif' || $status === 'Tidak Aktif') {
        $dosenlb->where('status', $status);
    } else {
        // Jika nilai status tidak valid, berikan respon error
        return ResponseFormatter::error('Data Dosen Luar Biasa not Found', 400);
    }
    }
    if($jabatan)
    {
        $dosenlb->where('jabatan', 'like', '%'.$jabatan.'%');

    }
    if($alamat_KTP)
    {
        $dosenlb->where('alamat_KTP', 'like', '%'.$alamat_KTP.'%');

    }
    if($alamat_saat_ini)
    {
        $dosenlb->where('alamat_saat_ini', 'like', '%'.$alamat_saat_ini.'%');

    }
    if($nama_bank_utama)
    {
        $dosenlb->where('nama_bank_utama', 'like', '%'.$nama_bank_utama.'%');

    }
    if($norek_bank_utama)
    {
        $dosenlb->where('norek_bank_utama', 'like', '%'.$norek_bank_utama.'%');

    }
    if($nama_bank_tambahan)
    {
        $dosenlb->where('nama_bank_tambahan', 'like', '%'.$nama_bank_tambahan.'%');

    }
    if($norek_bank_tambahan)
    {
        $dosenlb->where('norek_bank_utama', 'like', '%'.$norek_bank_utama.'%');

    }
    if ($golongan) {
        $dosenlb->where('golongan', 'like', '%'.$golongan.'%');
    }
    if($nomor_hp)
    {
        $dosenlb->where('nomor_hp', 'like', '%'.$nomor_hp.'%');

    }

    // Fetch Data ALL
    $dosenlb->with('banks');
    return ResponseFormatter::success(
        $dosenlb->paginate($limit),
        'Data Dosen Luar Biasa Found'
    );

 return ResponseFormatter::success($dosenlb, 'Data Dosen Luar Biasa Found');
}



    public function create(CreateDosenLuarBiasaRequest $dosenlbRequest, CreateBankRequest $bankRequest){
        // Memulai transaksi database
        DB::beginTransaction();
        try {

           // Create Dosen Luar Biasa
           $dosenlb = Dosen_Luar_Biasa::create([
            'nama' => $dosenlbRequest-> nama,
            'no_pegawai' => $dosenlbRequest-> no_pegawai,
            'npwp' => $dosenlbRequest-> npwp,
            'status' => $dosenlbRequest-> status,
            'golongan' => $dosenlbRequest-> golongan,
            'jabatan' => $dosenlbRequest-> jabatan,
            'alamat_KTP' => $dosenlbRequest-> alamat_KTP,
            'alamat_saat_ini' => $dosenlbRequest->  alamat_saat_ini,
            'nomor_hp' => $dosenlbRequest-> nomor_hp

        ]);

         // Create Data Bank
        //array bank
        $dosenlbBanks = [];
        foreach ($bankRequest->input('banks') as $bank) {
           $dosenlbBank = Doslb_Bank::create([
                'nama_bank' => $bank['nama_bank'],
                'no_rekening' => $bank['no_rekening'],
                'dosen_luar_biasa_id' => $dosenlb->id,
            ]);
            $dosenlbBanks[] = $dosenlbBank;
        }

         // Commit transaksi jika berhasil
         DB::commit();

        if(!$dosenlb && !$dosenlbBank){
            throw new Exception('Data Dosen Luar Biasa not created');
        }
        return ResponseFormatter::success(['dosenluarbiasa' => $dosenlb, 'banks' => $dosenlbBanks], 'Data Dosen Luar Biasa created');
    }catch(Exception $e){
        DB::rollback();
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

    public function update(UpdateDosenLuarBiasaRequest $dosenlbRequest, UpdateBankRequest $bankRequest, $id)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {

            // Get Dosen Luar Biasa
            $dosenlb = Dosen_Luar_Biasa::find($id);

            // Check if Dosen Luar Biasa exists
            if(!$dosenlb){
                throw new Exception('Data Dosen Luar Biasa not found');
            }

            // Update Dosen Luar Biasa
            $dosenlb -> update([
            'nama' => $dosenlbRequest-> nama,
            'no_pegawai' => $dosenlbRequest-> no_pegawai,
            'npwp' => $dosenlbRequest-> npwp,
            'status' => $dosenlbRequest-> status,
            'golongan' => $dosenlbRequest-> golongan,
            'jabatan' => $dosenlbRequest-> jabatan,
            'alamat_KTP' => $dosenlbRequest-> alamat_KTP,
            'alamat_saat_ini' => $dosenlbRequest->  alamat_saat_ini,
            'nomor_hp' => $dosenlbRequest-> nomor_hp

        ]);

          // Update Data Bank
          $dosenlbBanks = [];
          foreach ($bankRequest->input('banks') as $bank) {
              // Ambil ID bank dari objek bank yang diambil dari database
               $dosenlbBank = Doslb_Bank::find($bank['id']);

              // Check if Bank exists
              if (!$dosenlbBank) {
                  throw new Exception('Data Bank not found');
              }

              // Update data bank sesuai dengan ID
              $dosenlbBank->update([
                  'nama_bank' => $bank['nama_bank'],
                  'no_rekening' => $bank['no_rekening'],
                  'karyawan_id' => $dosenlbBank->dosen_luar_biasa_id
              ]);
              $dosenlbBanks[] = $dosenlbBank;
          }

           // Commit transaksi jika berhasil
           DB::commit();


        return ResponseFormatter::success(['dosenluarbiasa' => $dosenlb, 'banks' => $dosenlbBanks], 'Data Dosen Luar Biasa updated');
    }catch(Exception $e){
        DB::rollback();
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
        // Delete the related records with Dosen Luar Biasa
        $dosenlb->banks()->delete();

         // Delete the related records with Dosen Luar Biasa
         $dosenlb->master_transaksi()->each(function ($transaksi){
            // Delete related records
            $transaksi->komponen_pendapatan()->delete();
            $transaksi->potongan()->delete();
            $transaksi->pajak()->delete();
            $transaksi->delete();
        });

        // Delete Data Dosen Luar Biasa
        $dosenlb->delete();

        return ResponseFormatter::success('Data Dosen Luar Biasa deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
