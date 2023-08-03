<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Potongan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePotonganRequest;
use App\Http\Requests\UpdatePotonganRequest;
use App\Models\Potongan_Tambahan;

class PotonganController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $pegawai_id= $request->input('pegawai_id');
        $sp_FH= $request->input('sp_FH');
        $iiku = $request->input('iiku');
        $iid= $request->input('iid');
        $infaq= $request->input('infaq');
        $abt= $request->input('abt');
        $total_potongan= $request->input('total_potongan');
        $limit = $request->input('limit', 10);

        $potonganQuery = Potongan::query();

           // Get single data
    if($id)
    {
        $potongan= $potonganQuery->find($id);

        if($potongan){
            return ResponseFormatter::success($potongan, 'Data Potongan Pegawai found');
        }
            return ResponseFormatter::error('Data Potongan Pegawai not found', 404);
    }

    //    Get multiple Data
    $potongan = $potonganQuery;

    // // Get by attribute
    if($sp_FH)
    {
        $potongan->where('sp_FH', 'like', '%'.$sp_FH.'%');

    }
    if($iiku)
    {
        $potongan->where('iiku', 'like', '%'.$iiku.'%');
    }
    if($iid)
    {
        $potongan->where('iid', 'like', '%'.$iid.'%');
    }
    if($infaq)
    {
        $potongan->where('infaq', 'like', '%'.$infaq.'%');
    }
    if($abt)
    {
        $potongan->where('abt', 'like', '%'.$abt.'%');
    }
    if($total_potongan)
    {
        $potongan->where('total_potongan', 'like', '%'.$total_potongan.'%');

    }
    if ($pegawai_id) {
        $potongan->where('pegawai_id', $pegawai_id);
    }



    return ResponseFormatter::success(
        $potongan->paginate($limit),
        'Data Potongan Pegawai Found'
    );
    }



    public function create(CreatePotonganRequest $request){
        try{

            $potonganobjek = new Potongan;
            $total_potongan = $potonganobjek->total_potongan($request);

            // Create Potongan
            $potongan = Potongan::create([
                'pegawai_id' => $request-> pegawai_id,
                'sp_FH' => $request-> sp_FH,
                'iiku' => $request-> iiku,
                'iid' => $request-> iid,
                'infaq' => $request-> infaq,
                'abt' => $request-> abt,
                'total_potongan' => $total_potongan
            ]);
            if(!$potongan){
                throw new Exception('Data Potongan Pegawai not created');
            }
            return ResponseFormatter::success($potongan, 'Data Potongan Pegawai created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

        public function update(UpdatePotonganRequest $request, $id)
        {
            try {

                // Get Potongan
                $potongan = Potongan::find($id);

                // Check if potongan exists
                if(!$potongan){
                    throw new Exception('Data Potongan Pegawai not found');
                }

                // Menghitung total_potongan tanpa mempertimbangkan besar_potongan
                $total_potongan =
                $request-> sp_FH +
                $request-> iiku +
                $request-> iid +
                $request-> infaq +
                $request-> abt;

                // Mengecek apakah ada data besar_potongan dari tabel Potongan_Tambahan
                $potongan_tambahan = Potongan_Tambahan::where('potongan_id',$id)->whereNull('deleted_at')->get();
                if ($potongan_tambahan){
                    foreach($potongan_tambahan as $potongantambahan){
                        // Jika ada, tambahkan besar_potongan ke total_potongan
                    $total_potongan += $potongantambahan->besar_potongan;
                }
                    }

                // Update potongan
                $potongan -> update([
                    'pegawai_id' => $request-> pegawai_id,
                    'sp_FH' => $request-> sp_FH,
                    'iiku' => $request-> iiku,
                    'iid' => $request-> iid,
                    'infaq' => $request-> infaq,
                    'abt' => $request-> abt,
                    'total_potongan' => $total_potongan
            ]);
            return ResponseFormatter::success($potongan, 'Data Potongan Pegawai updated');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

    public function destroy($id){
        try{
            // Get Data Potongan
            $potongan = Potongan::find($id);

            // Check if Potongan exists
            if(!$potongan){
                throw new Exception('Data Potongan Pegawai not found');
            }

             // Delete the related Honor_Fakultas_Tambahan records
              $potongan->potongantambahan()->delete();

            // Delete Data Potongan
            $potongan->delete();

            return ResponseFormatter::success('Data Potongan Pegawai deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    }


