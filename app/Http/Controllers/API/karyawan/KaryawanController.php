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
    public function fetch()
    {
        $search = request()->q;
        $karyawan = Karyawan::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->with('banks')
            ->paginate(request()->per_page)
            ->toArray();

        $totalAktif = Karyawan::where('status', 'Aktif')
            ->count();
        $totalNonAktif = Karyawan::where('status', 'Tidak Aktif')
            ->count();
        $totalSemua = Karyawan::whereIn('status', ['Aktif', 'Tidak Aktif'])
            ->count();

        $i = $karyawan['from'];
        $hasil_data_karyawan = [];
        foreach ($karyawan['data'] as $data_karyawan) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_karyawan['id'];
            $row['nama'] = $data_karyawan['nama'];
            $row['no_pegawai'] = $data_karyawan['no_pegawai'];
            $row['npwp'] = $data_karyawan['npwp'];
            $row['status'] = $data_karyawan['status'];
            $row['golongan'] = $data_karyawan['golongan'];
            $row['jabatan'] = $data_karyawan['jabatan'];
            $row['alamat_KTP'] = $data_karyawan['alamat_KTP'];
            $row['alamat_saat_ini'] = $data_karyawan['alamat_saat_ini'];
            $row['nomor_hp'] = $data_karyawan['nomor_hp'];
            $row['banks'] = $data_karyawan['banks'];
            $row['totalAktif'] = $totalAktif;
            $row['totalNonAktif'] = $totalNonAktif;
            $row['totalSemua'] = $totalSemua;

            $hasil_data_karyawan[] = $row;
        }

        return ResponseFormatter::success(
            [
                'data' => $hasil_data_karyawan,
                'current_page' => $karyawan['current_page'],
                'first_page_url' => $karyawan['first_page_url'],
                'from' => $karyawan['from'],
                'last_page' => $karyawan['last_page'],
                'last_page_url' => $karyawan['last_page_url'],
                'links' => $karyawan['links'],
                'next_page_url' => $karyawan['next_page_url'],
                'path' => $karyawan['path'],
                'per_page' => $karyawan['per_page'],
                'prev_page_url' => $karyawan['prev_page_url'],
                'to' => $karyawan['to'],
                'total' => $karyawan['total'],
            ],
            'Data karyawan found'
        );
    }

    public function fetchById($id)
    {
        $data = Karyawan::find($id);
        return ResponseFormatter::success(
            [
                'data' => $data
            ],
            'Data karyawan found'
        );
    }

    public function create(CreateKaryawanRequest $karyawanRequest, CreateBankRequest $bankRequest)
    {
        // Memulai transaksi database
        DB::beginTransaction();
        try {

            // Create Karyawan
            $karyawan = Karyawan::create([
                'nama' => $karyawanRequest->nama,
                'no_pegawai' => $karyawanRequest->no_pegawai,
                'npwp' => $karyawanRequest->npwp,
                'status' => $karyawanRequest->status,
                'golongan' => $karyawanRequest->golongan,
                'jabatan' => $karyawanRequest->jabatan,
                'alamat_KTP' => $karyawanRequest->alamat_KTP,
                'alamat_saat_ini' => $karyawanRequest->alamat_saat_ini,
                'nomor_hp' => $karyawanRequest->nomor_hp

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


            if (!$karyawan && !$karyawanBank) {
                throw new Exception('Data Karyawan not created');
            }
            return ResponseFormatter::success(['karyawan' => $karyawan, 'banks' => $karyawanBanks], 'Data Karyawan created');
        } catch (Exception $e) {
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
            if (!$karyawan) {
                return ResponseFormatter::error('Data Karyawan not found', 404);
            }

            // Update Karyawan
            $karyawan->update([
                'nama' => $karyawanRequest->nama,
                'no_pegawai' => $karyawanRequest->no_pegawai,
                'npwp' => $karyawanRequest->npwp,
                'status' => $karyawanRequest->status,
                'golongan' => $karyawanRequest->golongan,
                'jabatan' => $karyawanRequest->jabatan,
                'alamat_KTP' => $karyawanRequest->alamat_KTP,
                'alamat_saat_ini' => $karyawanRequest->alamat_saat_ini,
                'nomor_hp' => $karyawanRequest->nomor_hp

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
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Get Data Karyawan
            $karyawan = Karyawan::find($id);

            // Check if Data Karyawan exists
            if (!$karyawan) {
                return ResponseFormatter::error('Data Karyawan not found', 404);
            }
            // Delete the related records with Karyawan
            $karyawan->banks()->delete();

            // Delete the related records with karyawan
            $karyawan->master_transaksi()->each(function ($transaksi) {
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
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
