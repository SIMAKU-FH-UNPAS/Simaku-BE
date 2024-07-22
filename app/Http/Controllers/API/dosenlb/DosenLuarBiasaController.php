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
    public function fetch()
    {
        $search = request()->q;
        $dosen_lb = Dosen_Luar_Biasa::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->with('banks')
            ->paginate(request()->per_page)
            ->toArray();

        $totalAktif = Dosen_Luar_Biasa::where('status', 'Aktif')
            ->count();
        $totalNonAktif = Dosen_Luar_Biasa::where('status', 'Tidak Aktif')
            ->count();
        $totalSemua = Dosen_Luar_Biasa::whereIn('status', ['Aktif', 'Tidak Aktif'])
            ->count();

        $i = $dosen_lb['from'];
        $hasil_data_dosen_lb = [];
        foreach ($dosen_lb['data'] as $data_dosen_lb) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_dosen_lb['id'];
            $row['nama'] = $data_dosen_lb['nama'];
            $row['no_pegawai'] = $data_dosen_lb['no_pegawai'];
            $row['npwp'] = $data_dosen_lb['npwp'];
            $row['status'] = $data_dosen_lb['status'];
            $row['golongan'] = $data_dosen_lb['golongan'];
            $row['jabatan'] = $data_dosen_lb['jabatan'];
            $row['alamat_KTP'] = $data_dosen_lb['alamat_KTP'];
            $row['alamat_saat_ini'] = $data_dosen_lb['alamat_saat_ini'];
            $row['nomor_hp'] = $data_dosen_lb['nomor_hp'];
            $row['banks'] = $data_dosen_lb['banks'];

            $hasil_data_dosen_lb[] = $row;
        }

        return ResponseFormatter::success(
            [
                'data' => $hasil_data_dosen_lb,
                'current_page' => $dosen_lb['current_page'],
                'first_page_url' => $dosen_lb['first_page_url'],
                'from' => $dosen_lb['from'],
                'last_page' => $dosen_lb['last_page'],
                'last_page_url' => $dosen_lb['last_page_url'],
                'links' => $dosen_lb['links'],
                'next_page_url' => $dosen_lb['next_page_url'],
                'path' => $dosen_lb['path'],
                'per_page' => $dosen_lb['per_page'],
                'prev_page_url' => $dosen_lb['prev_page_url'],
                'to' => $dosen_lb['to'],
                'total' => $dosen_lb['total'],
                'totalAktif' => $totalAktif,
                'totalNonAktif' => $totalNonAktif,
                'totalSemua' => $totalSemua,

            ],
            'Data Dosen LB found'
        );
    }

    public function fetchById($id)
    {
        $data = Dosen_Luar_Biasa::find($id);
        return ResponseFormatter::success(
            [
                'data' => $data
            ],
            'Data Dosen LB found'
        );
    }

    public function create(CreateDosenLuarBiasaRequest $dosenlbRequest, CreateBankRequest $bankRequest)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {

            // Create Dosen Luar Biasa
            $dosenlb = Dosen_Luar_Biasa::create([
                'nama' => $dosenlbRequest->nama,
                'no_pegawai' => $dosenlbRequest->no_pegawai,
                'npwp' => $dosenlbRequest->npwp,
                'status' => $dosenlbRequest->status,
                'golongan' => $dosenlbRequest->golongan,
                'jabatan' => $dosenlbRequest->jabatan,
                'alamat_KTP' => $dosenlbRequest->alamat_KTP,
                'alamat_saat_ini' => $dosenlbRequest->alamat_saat_ini,
                'nomor_hp' => $dosenlbRequest->nomor_hp
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

            if (!$dosenlb && !$dosenlbBank) {
                throw new Exception('Data Dosen Luar Biasa not created');
            }
            return ResponseFormatter::success(['dosenluarbiasa' => $dosenlb, 'banks' => $dosenlbBanks], 'Data Dosen Luar Biasa created');
        } catch (Exception $e) {
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
            if (!$dosenlb) {
                return ResponseFormatter::error('Data Dosen Luar Biasa not found', 404);
            }

            // Update Dosen Luar Biasa
            $dosenlb->update([
                'nama' => $dosenlbRequest->nama,
                'no_pegawai' => $dosenlbRequest->no_pegawai,
                'npwp' => $dosenlbRequest->npwp,
                'status' => $dosenlbRequest->status,
                'golongan' => $dosenlbRequest->golongan,
                'jabatan' => $dosenlbRequest->jabatan,
                'alamat_KTP' => $dosenlbRequest->alamat_KTP,
                'alamat_saat_ini' => $dosenlbRequest->alamat_saat_ini,
                'nomor_hp' => $dosenlbRequest->nomor_hp

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
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Data Dosen Luar Biasa
            $dosenlb = Dosen_Luar_Biasa::find($id);

            // Check if Data Luar Biasa exists
            if (!$dosenlb) {
                return ResponseFormatter::error('Data Dosen Luar Biasa not found', 404);
            }
            // Delete the related records with Dosen Luar Biasa
            $dosenlb->banks()->delete();

            // Delete the related records with Dosen Luar Biasa
            $dosenlb->master_transaksi()->each(function ($transaksi) {
                // Delete related records
                $transaksi->komponen_pendapatan()->delete();
                $transaksi->potongan()->delete();
                $transaksi->pajak()->delete();
                $transaksi->delete();
            });

            // Delete Data Dosen Luar Biasa
            $dosenlb->delete();

            return ResponseFormatter::success('Data Dosen Luar Biasa deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
