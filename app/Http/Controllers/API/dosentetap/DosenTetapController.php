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
    public function fetch()
    {
        $search = request()->q;
        $dosen_tetap = Dosen_Tetap::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->with('banks')
            ->paginate(request()->per_page)
            ->toArray();

        $i = $dosen_tetap['from'];
        $hasil_data_dosen_tetap = [];
        foreach ($dosen_tetap['data'] as $data_dosen_tetap) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_dosen_tetap['id'];
            $row['nama'] = $data_dosen_tetap['nama'];
            $row['no_pegawai'] = $data_dosen_tetap['no_pegawai'];
            $row['npwp'] = $data_dosen_tetap['npwp'];
            $row['status'] = $data_dosen_tetap['status'];
            $row['golongan'] = $data_dosen_tetap['golongan'];
            $row['jabatan'] = $data_dosen_tetap['jabatan'];
            $row['alamat_KTP'] = $data_dosen_tetap['alamat_KTP'];
            $row['alamat_saat_ini'] = $data_dosen_tetap['alamat_saat_ini'];
            $row['nomor_hp'] = $data_dosen_tetap['nomor_hp'];
            $row['banks'] = $data_dosen_tetap['banks'];

            $hasil_data_dosen_tetap[] = $row;
        }

        return ResponseFormatter::success(
            [
                'data' => $hasil_data_dosen_tetap,
                'current_page' => $dosen_tetap['current_page'],
                'first_page_url' => $dosen_tetap['first_page_url'],
                'from' => $dosen_tetap['from'],
                'last_page' => $dosen_tetap['last_page'],
                'last_page_url' => $dosen_tetap['last_page_url'],
                'links' => $dosen_tetap['links'],
                'next_page_url' => $dosen_tetap['next_page_url'],
                'path' => $dosen_tetap['path'],
                'per_page' => $dosen_tetap['per_page'],
                'prev_page_url' => $dosen_tetap['prev_page_url'],
                'to' => $dosen_tetap['to'],
                'total' => $dosen_tetap['total'],
            ],
            'Data Dosen Tetap found'
        );
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
