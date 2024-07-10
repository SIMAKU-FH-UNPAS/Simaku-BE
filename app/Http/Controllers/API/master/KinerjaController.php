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
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $nama = $request->input('nama');
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $limit = $request->input('limit', 3);

        $kinerjaQuery = Kinerja::query();

        if ($id) {
            $fungsional = $kinerjaQuery
                ->find($id);

            if ($fungsional) {
                return ResponseFormatter::success($fungsional, 'Data Kinerja found');
            }
            return ResponseFormatter::error('Data Kinerja not found', 404);
        }

        $fungsional = $kinerjaQuery;

        if ($nama) {
            $kinerjaQuery->where('nama', 'like', '%' . $nama . '%');
        }

        return ResponseFormatter::success(
            $fungsional->paginate($limit),
            'Data Kinerja Found'
        );

        return ResponseFormatter::success($fungsional, 'Data Kinerja Found');
    }

    public function create(Request $request)
    {
        $this->_validate($request);

        DB::beginTransaction();

        try {

            $fungsional = Kinerja::create([
                'nama' => $request->input('nama'),
                'tgl_awal' => $request->input('tgl_awal'),
                'tgl_akhir' => $request->input('tgl_akhir'),
            ]);

            DB::commit();

            if (!$fungsional) {
                throw new Exception('Data Dosen Tetap not created');
            }

            return ResponseFormatter::success(
                [
                    'fungsional' => $fungsional
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

            $fungsional = Kinerja::find($id);

            if (!$fungsional) {
                return ResponseFormatter::error('Data Kinerja not found', 404);
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
            $fungsional = Kinerja::find($id);
            $fungsional->delete();

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
