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
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $nama = $request->input('nama');
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $limit = $request->input('limit', 10);

        $tambahanQuery = Tambahan::query();

        if ($id) {
            $fungsional = $tambahanQuery
                ->find($id);

            if ($fungsional) {
                return ResponseFormatter::success($fungsional, 'Data Tambahan found');
            }
            return ResponseFormatter::error('Data Tambahan not found', 404);
        }

        $fungsional = $tambahanQuery;

        if ($nama) {
            $tambahanQuery->where('nama', 'like', '%' . $nama . '%');
        }

        return ResponseFormatter::success(
            $fungsional->paginate($limit),
            'Data Tambahan Found'
        );

        return ResponseFormatter::success($fungsional, 'Data Tambahan Found');
    }

    public function create(Request $request)
    {
        $this->_validate($request);

        DB::beginTransaction();

        try {

            $fungsional = Tambahan::create([
                'nama' => $request->input('nama'),
                'tgl_awal' => $request->input('tgl_awal'),
                'tgl_akhir' => $request->input('tgl_akhir'),
            ]);

            DB::commit();

            if (!$fungsional) {
                throw new Exception('Data Tambahan not created');
            }

            return ResponseFormatter::success(
                [
                    'fungsional' => $fungsional
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

            $fungsional = Tambahan::find($id);

            if (!$fungsional) {
                return ResponseFormatter::error('Data Tambahan not found', 404);
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
            $fungsional = Tambahan::find($id);
            $fungsional->delete();

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
