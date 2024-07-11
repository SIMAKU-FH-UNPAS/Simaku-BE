<?php

namespace App\Http\Controllers\API\master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\master\Tambahan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TambahanController extends Controller
{
    public function fetch()
    {
        $search = request()->q;
        $tambahan = Tambahan::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->paginate(request()->per_page)->toArray();

        $i = $tambahan['from'];
        $hasil_data_tambahan = [];
        foreach ($tambahan['data'] as $data_tambahan) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_tambahan['id'];
            $row['nama'] = $data_tambahan['nama'];
            $row['tgl_awal'] = $data_tambahan['tgl_awal'];
            $row['tgl_akhir'] = $data_tambahan['tgl_akhir'];

            $hasil_data_tambahan[] = $row;
        }

        return ResponseFormatter::success(
            [
                'data' => $hasil_data_tambahan,
                'current_page' => $tambahan['current_page'],
                'first_page_url' => $tambahan['first_page_url'],
                'from' => $tambahan['from'],
                'last_page' => $tambahan['last_page'],
                'last_page_url' => $tambahan['last_page_url'],
                'links' => $tambahan['links'],
                'next_page_url' => $tambahan['next_page_url'],
                'path' => $tambahan['path'],
                'per_page' => $tambahan['per_page'],
                'prev_page_url' => $tambahan['prev_page_url'],
                'to' => $tambahan['to'],
                'total' => $tambahan['total'],
            ],
            'Data tambahan found'
        );
    }

    public function create(Request $request)
    {
        $this->_validate($request);

        DB::beginTransaction();

        try {

            $tambahan = new Tambahan();
            $tambahan->nama = $request->input('nama');
            $tambahan->tgl_awal = $request->input('tgl_awal');
            $tambahan->tgl_akhir = $request->input('tgl_akhir');
            $tambahan->save();

            DB::commit();

            if (!$tambahan) {
                throw new Exception('Data Tambahan not created');
            }

            return ResponseFormatter::success(
                [
                    'tambahan' => $tambahan
                ],
                'Data Tambahan created'
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

            $tambahan = Tambahan::find($id);

            if (!$tambahan) {
                return ResponseFormatter::error('Data Tambahan not found', 404);
            }

            $tambahan->update([
                'nama' => $request->input('nama'),
                'tgl_awal' => $request->input('tgl_awal'),
                'tgl_akhir' => $request->input('tgl_akhir'),
            ]);

            DB::commit();

            return ResponseFormatter::success(
                [
                    'tambahan' => $tambahan
                ],
                'Data Tambahan updated'
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
            $tambahan = Tambahan::find($id);
            $tambahan->delete();

            DB::commit();
            return ResponseFormatter::success('Data Tambahan deleted');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    private function _validate($request)
    {
        $validator = $request->validate([
            'nama' => ['required'],
            'tgl_awal' => ['required'],
            'tgl_akhir' => ['required'],
        ], [
            'nama.required' => "Nama tidak boleh kosong",
            'tgl_awal.required' => "Tanggal Awal tidak boleh kosong",
            'tgl_akhir.required' => "Tanggal Akhir boleh kosong",
        ]);

        return $validator;
    }
}
