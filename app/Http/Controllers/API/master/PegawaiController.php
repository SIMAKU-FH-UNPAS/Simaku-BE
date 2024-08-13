<?php

namespace App\Http\Controllers\API\master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\master\Pegawai;
use App\Models\pegawai\KinerjaTambahan;
use App\Models\pegawai\PegawaiBank;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function fetch()
    {
        $search = request()->q;
        $filter = request()->filter;
        $pegawai = Pegawai::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->when($filter, function ($posts, $filter) {
                $posts = $posts->where('tipe_pegawai', 'LIKE', '%' . $filter . '%');
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
            ->with('banks', 'kinerja_tambahan.kinerja')
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

        try {
            DB::beginTransaction();

            $pegawai = Pegawai::create([
                'nama' => $request->input('nama'),
                'no_pegawai' => $request->input('no_pegawai'),
                'npwp' => $request->input('npwp'),
                'status' => $request->input('status'),
                'golongan' => $request->input('golongan'),
                'tipe_pegawai' => $request->input('tipe_pegawai'),
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
        $this->_validateUpdate($request);

        try {
            DB::beginTransaction();

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
                'tipe_pegawai' => $request->input('tipe_pegawai'),
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
        try {
            DB::beginTransaction();
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
            'tipe_pegawai' => 'required|string|in:dosen tetap,dosen lb,karyawan tetap,karyawan kontrak universitas,karyawan kontrak fakultas',
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

    private function _validateUpdate($request)
    {
        $validator = $request->validate([
            'nama' => 'required|string|max:255',
            // 'no_pegawai' => 'required|string|max:255|unique:dosen_tetap,no_pegawai,NULL,id,deleted_at,NULL',
            'npwp' => 'string|max:255',
            'status' => 'required|string|in:Aktif,Tidak Aktif',
            'golongan' => 'required|string|in:IIA,IIB,IIC,IID,IIIA,IIIB,IIIC,IIID,IVA,IVB,IVC,IVD,IVE',
            'tipe_pegawai' => 'required|string|in:dosen tetap,dosen lb,karyawan tetap,karyawan kontrak universitas,karyawan kontrak fakultas',
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

    public function kinerjaTambahan()
    {
        $search = request()->q;
        $kinerja = Pegawai::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->with('kinerja_tambahan.kinerja')
            ->paginate(request()->per_page)->toArray();

        $i = $kinerja['from'];
        $hasil_data_kinerja = [];
        foreach ($kinerja['data'] as $data_kinerja) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_kinerja['id'];
            $row['nama'] = $data_kinerja['nama'];
            $row['status'] = $data_kinerja['status'];
            $row['golongan'] = $data_kinerja['golongan'];
            $row['tipe_pegawai'] = $data_kinerja['tipe_pegawai'];
            $row['jabatan'] = $data_kinerja['jabatan'];
            $row['kinerja_tambahan'] = $data_kinerja['kinerja_tambahan'];

            $hasil_data_kinerja[] = $row;
        }

        return ResponseFormatter::success(
            [
                'data' => $hasil_data_kinerja,
                'current_page' => $kinerja['current_page'],
                'first_page_url' => $kinerja['first_page_url'],
                'from' => $kinerja['from'],
                'last_page' => $kinerja['last_page'],
                'last_page_url' => $kinerja['last_page_url'],
                'links' => $kinerja['links'],
                'next_page_url' => $kinerja['next_page_url'],
                'path' => $kinerja['path'],
                'per_page' => $kinerja['per_page'],
                'prev_page_url' => $kinerja['prev_page_url'],
                'to' => $kinerja['to'],
                'total' => $kinerja['total'],
            ],
            'Data kinerja tambahan found'
        );
    }

    public function kinerjaTambahanById($id)
    {
        $search = request()->q;
        $pegawai = Pegawai::leftJoin('kinerja_tambahans as b', 'pegawais.id', '=', 'b.pegawais_id')
            ->Join('kinerjas as c', 'c.id', '=', 'b.kinerjas_id')
            ->whereNull('b.deleted_at')
            ->where('pegawais.id', $id)
            ->selectRaw('pegawais.id as id, pegawais.nama, b.id as id_kinerja_tambahan, c.nama as kinerja_tambahan, b.tgl_awal, b.tgl_akhir')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('c.nama', 'LIKE', '%' . $search . '%');
            })
            ->paginate(request()->per_page)->toArray();

        $i = $pegawai['from'];
        $hasil_data_pegawai = [];
        foreach ($pegawai['data'] as $data_pegawai) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_pegawai['id'];
            $row['nama'] = $data_pegawai['nama'];
            $row['id_kinerja_tambahan'] = $data_pegawai['id_kinerja_tambahan'];
            $row['kinerja_tambahan'] = $data_pegawai['kinerja_tambahan'];
            $row['tgl_awal'] = $data_pegawai['tgl_awal'];
            $row['tgl_akhir'] = $data_pegawai['tgl_akhir'];

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
            'Data Kinerja Tambahan Pegawai found'
        );
    }

    public function createKinerja(Request $request)
    {
        $this->_validateKinerja($request);

        try {
            DB::beginTransaction();

            $kinerjaTambahan = new KinerjaTambahan();
            $kinerjaTambahan->pegawais_id = $request->input('pegawais_id');
            $kinerjaTambahan->kinerjas_id = $request->input('kinerjas_id');
            $kinerjaTambahan->tgl_awal = $request->input('tgl_awal');
            $kinerjaTambahan->tgl_akhir = $request->input('tgl_akhir');
            $kinerjaTambahan->save();

            DB::commit();

            if (!$kinerjaTambahan) {
                throw new Exception('Data Kinerja Tambahan not created');
            }

            return ResponseFormatter::success(
                [
                    'kinerjaTambahan' => $kinerjaTambahan
                ],
                'Data Kinerja Tambahan created'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function updateKinerja(Request $request, $id)
    {
        $this->_validateKinerja($request);
        try {
            DB::beginTransaction();

            $kinerjaTambahan = KinerjaTambahan::find($id);
            if (!$kinerjaTambahan) {
                return ResponseFormatter::error('Data Kinerja Tambahan not found', 404);
            }

            $kinerjaTambahan->update([
                'pegawais_id' => $request->input('pegawais_id'),
                'kinerjas_id' => $request->input('kinerjas_id'),
                'tgl_awal' => $request->input('tgl_awal'),
                'tgl_akhir' => $request->input('tgl_akhir'),
            ]);

            DB::commit();
            return ResponseFormatter::success('Data Kinerja Tambahan updated');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroyKinerja($id)
    {
        try {
            DB::beginTransaction();

            $kinerjaTambahan = KinerjaTambahan::find($id);
            if (!$kinerjaTambahan) {
                return ResponseFormatter::error('Data Kinerja Tambahan not found', 404);
            }

            $kinerjaTambahan->delete();

            DB::commit();
            return ResponseFormatter::success('Data Kinerja Tambahan deleted');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function _validateKinerja($request)
    {
        $validator = $request->validate([
            'pegawais_id' => 'required',
            'kinerjas_id' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
        ]);

        return $validator;
    }
}
