<?php

namespace App\Http\Controllers\API\master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\master\Fungsional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FungsionalController extends Controller
{
    public function fetch()
    {
        $search = request()->q;
        $fungsional = Fungsional::select('*')
            ->orderBy(request()->sortby, request()->sortbydesc)
            ->when($search, function ($posts, $search) {
                $posts = $posts->where('nama', 'LIKE', '%' . $search . '%');
            })
            ->paginate(request()->per_page)->toArray();

        $i = $fungsional['from'];
        $hasil_data_fungsional = [];
        foreach ($fungsional['data'] as $data_fungsional) {
            $row = [];
            $row['no'] = $i++;
            $row['id'] = $data_fungsional['id'];
            $row['nama'] = $data_fungsional['nama'];
            $row['tgl_awal'] = $data_fungsional['tgl_awal'];
            $row['tgl_akhir'] = $data_fungsional['tgl_akhir'];

            $hasil_data_fungsional[] = $row;
        }

        return ResponseFormatter::success(
            [
                'data' => $hasil_data_fungsional,
                'current_page' => $fungsional['current_page'],
                'first_page_url' => $fungsional['first_page_url'],
                'from' => $fungsional['from'],
                'last_page' => $fungsional['last_page'],
                'last_page_url' => $fungsional['last_page_url'],
                'links' => $fungsional['links'],
                'next_page_url' => $fungsional['next_page_url'],
                'path' => $fungsional['path'],
                'per_page' => $fungsional['per_page'],
                'prev_page_url' => $fungsional['prev_page_url'],
                'to' => $fungsional['to'],
                'total' => $fungsional['total'],
            ],
            'Data fungsional found'
        );
    }

    public function create(Request $request)
    {
        $this->_validate($request);

        DB::beginTransaction();

        try {

            $fungsional = new Fungsional();
            $fungsional->nama = $request->input('nama');
            $fungsional->tgl_awal = $request->input('tgl_awal');
            $fungsional->tgl_akhir = $request->input('tgl_akhir');
            $fungsional->save();

            DB::commit();

            if (!$fungsional) {
                throw new Exception('Data Dosen Tetap not created');
            }

            return ResponseFormatter::success(
                [
                    'fungsional' => $fungsional
                ],
                'Data Fungsional created'
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

            $fungsional = Fungsional::find($id);

            if (!$fungsional) {
                return ResponseFormatter::error('Data Fungsional not found', 404);
            }

            $fungsional->update([
                'nama' => $request->input('nama'),
                'tgl_awal' => $request->input('tgl_awal'),
                'tgl_akhir' => $request->input('tgl_akhir'),
            ]);

            DB::commit();

            return ResponseFormatter::success(
                [
                    'fungsional' => $fungsional
                ],
                'Data Fungsional updated'
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
            $fungsional = Fungsional::find($id);
            $fungsional->delete();

            DB::commit();
            return ResponseFormatter::success('Data Fungsional deleted');
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
