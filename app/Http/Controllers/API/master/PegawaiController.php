<?php

namespace App\Http\Controllers\API\master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\master\Pegawai;
use App\Models\pegawai\PegawaiBank;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function fetch()
    {
        $search = request()->q;
        $pegawai = Pegawai::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->with('banks')
            ->paginate(request()->per_page)->toArray();

        $i = $pegawai['from'];
        $hasil_data_pegawai = [];
        foreach ($pegawai['data'] as $data_pegawai) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_pegawai['id'];
            $row['nama'] = $data_pegawai['nama'];
            $row['no_pegawai'] = $data_pegawai['no_pegawai'];
            $row['npwp'] = $data_pegawai['npwp'];
            $row['status'] = $data_pegawai['status'];
            $row['golongan'] = $data_pegawai['golongan'];
            $row['tipe_pegawai'] = $data_pegawai['tipe_pegawai'];
            $row['jabatan'] = $data_pegawai['jabatan'];
            $row['alamat_ktp'] = $data_pegawai['alamat_ktp'];
            $row['alamat_saat_ini'] = $data_pegawai['alamat_saat_ini'];
            $row['nomor_hp'] = $data_pegawai['nomor_hp'];
            $row['banks'] = $data_pegawai['banks'];

            $hasil_data_pegawai[] = $row;
        }

        return ResponseFormatter::success(
            [
                'data' => $hasil_data_pegawai,
                'current_page' => $pegawai['current_page'],
                'first_page_url' => $pegawai['first_page_url'],
                'from' => $pegawai['from'],
                'last_page' => $pegawai['last_page'],
                'last_page_url' => $pegawai['last_page_url'],
                'links' => $pegawai['links'],
                'next_page_url' => $pegawai['next_page_url'],
                'path' => $pegawai['path'],
                'per_page' => $pegawai['per_page'],
                'prev_page_url' => $pegawai['prev_page_url'],
                'to' => $pegawai['to'],
                'total' => $pegawai['total'],
            ],
            'Data pegawai found'
        );
    }

    public function fetchById($id)
    {
        $data = Pegawai::where('id', $id)
            ->with('banks')
            ->first();

        return ResponseFormatter::success(
            [
                'data' => $data
            ],
            'Data pegawai found'
        );
    }

    public function create(Request $request)
    {
        $this->_validate($request);

        DB::beginTransaction();
        try {
            $pegawai = Pegawai::create([
                'nama' => $request->input('nama'),
                'no_pegawai' => $request->input('no_pegawai'),
                'npwp' => $request->input('npwp'),
                'status' => $request->input('status'),
                'golongan' => $request->input('golongan'),
                'jabatan' => $request->input('jabatan'),
                'alamat_ktp' => $request->input('alamat_ktp'),
                'alamat_saat_ini' => $request->input('alamat_saat_ini'),
                'nomor_hp' => $request->input('nomor_hp')
            ]);

            $pegawaiBanks = [];
            foreach ($request->input('banks') as $bank) {
                $pegawaiBank = PegawaiBank::create([
                    'nama_bank' => $bank['nama_bank'],
                    'no_rekening' => $bank['no_rekening'],
                    'pegawais_id' => $pegawai->id,
                ]);
                $pegawaiBanks[] = $pegawaiBank;
            }

            DB::commit();

            if (!$pegawai && !$pegawaiBank) {
                throw new Exception('Data Pegawai not created');
            }

            return ResponseFormatter::success(
                [
                    'pegawai' => $pegawai,
                    'banks' => $pegawaiBanks
                ],
                'Data Pegawai created'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $this->_validate($request);

        DB::beginTransaction();
        try {

            $pegawai = Pegawai::find($id);
            if (!$pegawai) {
                return ResponseFormatter::error('Data Pegawai not found', 404);
            }

            $pegawai->update([
                'nama' => $request->input('nama'),
                'no_pegawai' => $request->input('no_pegawai'),
                'npwp' => $request->input('npwp'),
                'status' => $request->input('status'),
                'golongan' => $request->input('golongan'),
                'jabatan' => $request->input('jabatan'),
                'alamat_ktp' => $request->input('alamat_ktp'),
                'alamat_saat_ini' => $request->input('alamat_saat_ini'),
                'nomor_hp' => $request->input('nomor_hp')
            ]);

            $pegawaiBanks = [];
            foreach ($request->input('banks') as $bank) {
                $pegawaiBank = PegawaiBank::find($bank['id']);

                if (!$pegawaiBank) {
                    return ResponseFormatter::error('Data Bank not found', 404);
                }

                $pegawaiBank->update([
                    'nama_bank' => $bank['nama_bank'],
                    'no_rekening' => $bank['no_rekening'],
                    'pegawais_id' => $pegawaiBank->pegawais_id
                ]);
                $pegawaiBanks[] = $pegawaiBank;
            }

            DB::commit();

            return ResponseFormatter::success(
                [
                    'pegawai' => $pegawai,
                    'banks' => $pegawaiBanks
                ],
                'Data Pegawai updated'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pegawai = Pegawai::find($id);

            if (!$pegawai) {
                return ResponseFormatter::error('Data Pegawai not found', 404);
            }
            $pegawai->banks()->delete();

            // master transaksi disini

            $pegawai->delete();

            DB::commit();
            return ResponseFormatter::success('Data Pegawai deleted');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    private function _validate($request)
    {
        $validator = $request->validate([
            'nama' => 'required|string|max:255',
            'no_pegawai' => 'required|string|max:255|unique:dosen_tetap,no_pegawai,NULL,id,deleted_at,NULL',
            'npwp' => 'string|max:255',
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'golongan' => 'required|string|in:IIA,IIB,IIC,IID,IIIA,IIIB,IIIC,IIID,IVA,IVB,IVC,IVD,IVE',
            'jabatan' => 'required|string|max:255',
            'alamat_ktp' => 'required|string|max:255',
            'alamat_saat_ini' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:255',
            'banks' => 'nullable|array',
            'banks.*.nama_bank' => 'nullable|string',
            'banks.*.no_rekening' => 'nullable|string'
        ]);

        return $validator;
    }
}
