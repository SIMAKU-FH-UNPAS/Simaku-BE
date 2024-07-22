<?php

namespace App\Http\Controllers\API\master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\master\Kinerja;
use App\Models\master\KinerjaKinerja;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KinerjaController extends Controller
{
    public function fetch()
    {
        $search = request()->q;
        $kinerja = Kinerja::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->paginate(request()->per_page)->toArray();

        $i = $kinerja['from'];
        $hasil_data_kinerja = [];
        foreach ($kinerja['data'] as $data_kinerja) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_kinerja['id'];
            $row['nama'] = $data_kinerja['nama'];
            $row['jenis'] = $data_kinerja['jenis'];
            $row['nominal'] = $data_kinerja['nominal'];

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
            'Data kinerja found'
        );
    }

    public function fetchById($id)
    {
        $data = Kinerja::find($id);
        return ResponseFormatter::success(
            [
                'data' => $data
            ],
            'Data kinerja found'
        );
    }

    public function create(Request $request)
    {
        $this->_validate($request);

        DB::beginTransaction();

        try {

            $kinerja = new Kinerja();
            $kinerja->nama = $request->input('nama');
            $kinerja->jenis = $request->input('jenis');
            $kinerja->nominal = $request->input('nominal');
            $kinerja->save();

            DB::commit();

            if (!$kinerja) {
                throw new Exception('Data Dosen Tetap not created');
            }

            return ResponseFormatter::success(
                [
                    'kinerja' => $kinerja
                ],
                'Data Kinerja created'
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

            $kinerja = Kinerja::find($id);

            if (!$kinerja) {
                return ResponseFormatter::error('Data Kinerja not found', 404);
            }

            $kinerja->update([
                'nama' => $request->input('nama'),
                'jenis' => $request->input('jenis'),
                'nominal' => $request->input('nominal'),
            ]);

            DB::commit();

            return ResponseFormatter::success(
                [
                    'kinerja' => $kinerja
                ],
                'Data Kinerja updated'
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
            $kinerja = Kinerja::find($id);
            $kinerja->delete();

            DB::commit();
            return ResponseFormatter::success('Data Kinerja deleted');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    private function _validate($request)
    {
        $validator = $request->validate([
            'nama' => ['required'],
            'jenis' => ['required'],
        ], [
            'nama.required' => "Nama tidak boleh kosong",
            'jenis.required' => "Tanggal Awal tidak boleh kosong",
        ]);

        return $validator;
    }
}
