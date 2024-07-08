<?php

namespace App\Http\Controllers\API\dosentetap;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\dosentetap\Dosen_Tetap;
use App\Models\dosentetap\Dostap_Bank;
use App\Http\Requests\dosentetap\CreateBankRequest;
use App\Http\Requests\dosentetap\UpdateBankRequest;
use App\Http\Requests\dosentetap\CreateDosenTetapRequest;
use App\Http\Requests\dosentetap\UpdateDosenTetapRequest;


class DosenTetapController extends Controller
{
    public function fetch(Request $request)
    {
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

        $dosentetapQuery = Dosen_Tetap::query();


        // Get single data
        if ($id) {
            $dosentetap = $dosentetapQuery->with('banks')->find($id);

            if ($dosentetap) {
                return ResponseFormatter::success($dosentetap, 'Data Dosen Tetap found');
            }
            return ResponseFormatter::error('Data Dosen Tetap not found', 404);
        }

        //    Get multiple Data
        $dosentetap = $dosentetapQuery;

        // Get by attribute
        if ($nama) {
            $dosentetap->where('nama', 'like', '%' . $nama . '%');
        }
        if ($no_pegawai) {
            $dosentetap->where('no_pegawai', 'like', '%' . $no_pegawai . '%');
        }
        if ($npwp) {
            $dosentetap->where('npwp', 'like', '%' . $npwp . '%');
        }
        if ($status) {
            // Filter berdasarkan status "aktif" atau "tidak aktif"
            if ($status === 'Aktif' || $status === 'Tidak Aktif') {
                $dosentetap->where('status', $status);
            } else {
                // Jika nilai status tidak valid, berikan respon error
                return ResponseFormatter::error('Data Dosen Tetap not Found', 404);
            }
        }
        if ($jabatan) {
            $dosentetap->where('jabatan', 'like', '%' . $jabatan . '%');
        }
        if ($alamat_KTP) {
            $dosentetap->where('alamat_KTP', 'like', '%' . $alamat_KTP . '%');
        }
        if ($alamat_saat_ini) {
            $dosentetap->where('alamat_saat_ini', 'like', '%' . $alamat_saat_ini . '%');
        }
        if ($golongan) {
            $dosentetap->where('golongan', 'like', '%' . $golongan . '%');
        }
        if ($nomor_hp) {
            $dosentetap->where('nomor_hp', 'like', '%' . $nomor_hp . '%');
        }

        //Fetch All Data
        $dosentetap->with('banks');

        return ResponseFormatter::success(
            $dosentetap->paginate($limit),
            'Data Dosen Tetap Found'
        );

        return ResponseFormatter::success($dosentetap, 'Data Dosen Tetap Found');
    }



    public function create(CreateDosenTetapRequest $dosentetapRequest, CreateBankRequest $bankRequest)
    {

        // Memulai transaksi database
        DB::beginTransaction();

        try {
            // Create Dosen Tetap
            $dosentetap = Dosen_Tetap::create([
                'nama' => $dosentetapRequest->nama,
                'no_pegawai' => $dosentetapRequest->no_pegawai,
                'npwp' => $dosentetapRequest->npwp,
                'status' => $dosentetapRequest->status,
                'golongan' => $dosentetapRequest->golongan,
                'jabatan' => $dosentetapRequest->jabatan,
                'alamat_KTP' => $dosentetapRequest->alamat_KTP,
                'alamat_saat_ini' => $dosentetapRequest->alamat_saat_ini,
                'nomor_hp' => $dosentetapRequest->nomor_hp
            ]);

            // Create Data Bank
            //array bank
            $dostapBanks = [];
            foreach ($bankRequest->input('banks') as $bank) {
                $dostapBank = Dostap_Bank::create([
                    'nama_bank' => $bank['nama_bank'],
                    'no_rekening' => $bank['no_rekening'],
                    'dosen_tetap_id' => $dosentetap->id,
                ]);
                $dostapBanks[] = $dostapBank;
            }

            // Commit transaksi jika berhasil
            DB::commit();

            if (!$dosentetap && !$dostapBank) {
                throw new Exception('Data Dosen Tetap not created');
            }
            return ResponseFormatter::success(['dosentetap' => $dosentetap, 'banks' => $dostapBanks], 'Data Dosen Tetap created');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(UpdateDosenTetapRequest $dosentetapRequest, UpdateBankRequest $bankRequest, $id)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {

            // Get Dosen Tetap
            $dosentetap = Dosen_Tetap::find($id);

            // Check if Dosen Tetap exists
            if (!$dosentetap) {
                return ResponseFormatter::error('Data Dosen Tetap not found', 404);
            }

            // Update Dosen Tetap
            $dosentetap->update([
                'nama' => $dosentetapRequest->nama,
                'no_pegawai' => $dosentetapRequest->no_pegawai,
                'npwp' => $dosentetapRequest->npwp,
                'status' => $dosentetapRequest->status,
                'golongan' => $dosentetapRequest->golongan,
                'jabatan' => $dosentetapRequest->jabatan,
                'alamat_KTP' => $dosentetapRequest->alamat_KTP,
                'alamat_saat_ini' => $dosentetapRequest->alamat_saat_ini,
                'nomor_hp' => $dosentetapRequest->nomor_hp

            ]);

            // Update Data Bank
            $dostapBanks = [];
            foreach ($bankRequest->input('banks') as $bank) {
                // Ambil ID bank dari objek bank yang diambil dari database
                $dostapBank = Dostap_Bank::find($bank['id']);

                // Check if Bank exists
                if (!$dostapBank) {
                    return ResponseFormatter::error('Data Bank not found', 404);
                }

                // Update data bank sesuai dengan ID
                $dostapBank->update([
                    'nama_bank' => $bank['nama_bank'],
                    'no_rekening' => $bank['no_rekening'],
                    'dosen_tetap_id' => $dostapBank->dosen_tetap_id
                ]);
                $dostapBanks[] = $dostapBank;
            }

            // Commit transaksi jika berhasil
            DB::commit();


            return ResponseFormatter::success(['dosentetap' => $dosentetap, 'banks' => $dostapBanks], 'Data Dosen Tetap updated');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Data Dosen Tetap
            $dosentetap = Dosen_Tetap::find($id);

            // Check if Data Tetap exists
            if (!$dosentetap) {
                return ResponseFormatter::error('Data Dosen Tetap not found', 404);
            }
            // Delete the related records with Dosen Tetap
            $dosentetap->banks()->delete();

            // Delete the related records with karyawan
            $dosentetap->master_transaksi()->each(function ($transaksi) {
                // Delete related records
                $transaksi->gaji_universitas()->delete();
                $transaksi->gaji_fakultas()->delete();
                $transaksi->potongan()->delete();
                $transaksi->pajak()->delete();
                $transaksi->delete();
            });

            // Delete Data Dosen Tetap
            $dosentetap->delete();


            return ResponseFormatter::success('Data Dosen Tetap deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
