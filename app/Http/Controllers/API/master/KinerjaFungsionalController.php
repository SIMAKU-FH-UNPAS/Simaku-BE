<?php

namespace App\Http\Controllers\API\master;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\master\KinerjaFungsional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KinerjaFungsionalController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $dosen_id = $request->input('dosen_id');
        $fungsional_id = $request->input('fungsional_id');
        $limit = $request->input('limit', 10);

        $kinerjaFungsionalQuery = KinerjaFungsional::query();

        if ($id) {
            $kinerjaFungsional = $kinerjaFungsionalQuery
                ->find($id);

            if ($kinerjaFungsional) {
                return ResponseFormatter::success($kinerjaFungsional, 'Data Kinerja Fungsional found');
            }
            return ResponseFormatter::error('Data Kinerja Fungsional not found', 404);
        }

        $kinerjaFungsional = $kinerjaFungsionalQuery;

        if ($fungsional_id) {
            $kinerjaFungsionalQuery->where('fungsional_id',  $fungsional_id);
        }

        return ResponseFormatter::success(
            $kinerjaFungsional->paginate($limit),
            'Data Kinerja Fungsional Found'
        );

        return ResponseFormatter::success($kinerjaFungsional, 'Data Kinerja Fungsional Found');
    }

    public function create(Request $request)
    {
        $this->_validate($request);

        DB::beginTransaction();

        try {

            $kinerjaFungsional = KinerjaFungsional::create([
                'dosen_id' => $request->input('dosen_id'),
                'fungsional_id' => $request->input('fungsional_id'),
            ]);

            DB::commit();

            if (!$kinerjaFungsional) {
                throw new Exception('Data Kinerja Fungsional not created');
            }

            return ResponseFormatter::success(
                [
                    'kinerjaFungsional' => $kinerjaFungsional
                ],
                'Data Kinerja Fungsional created'
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

            $kinerjaFungsional = KinerjaFungsional::find($id);

            if (!$kinerjaFungsional) {
                return ResponseFormatter::error('Data Kinerja Fungsional not found', 404);
            }

            $kinerjaFungsional->update([
                'dosen_id' => $request->input('dosen_id'),
                'fungsional_id' => $request->input('fungsional_id'),
            ]);

            DB::commit();

            return ResponseFormatter::success(
                [
                    'kinerjaFungsional' => $kinerjaFungsional
                ],
                'Data Kinerja Fungsional updated'
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
            $kinerjaFungsional = KinerjaFungsional::find($id);
            $kinerjaFungsional->delete();

            DB::commit();
            return ResponseFormatter::success('Data Kinerja Fungsional deleted');
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    private function _validate($request)
    {
        $validator = $request->validate([
            'dosen_id' => ['required'],
            'fungsional_id' => ['required'],
        ], [
            'dosen_id.required' => "Dosen tidak boleh kosong",
            'fungsional_id.required' => "Fungsional tidak boleh kosong",
        ]);

        return $validator;
    }
}
