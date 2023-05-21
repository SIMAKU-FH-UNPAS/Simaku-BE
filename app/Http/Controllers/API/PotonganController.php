<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Potongan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePotonganRequest;
use App\Http\Requests\UpdatePotonganRequest;

class PotonganController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $jenis_potongan= $request->input('jenis_potongan');
        $besar_potongan = $request->input('besar_potongan');
        $pegawai_id= $request->input('pegawai_id');
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
    if($jenis_potongan)
    {
        $potongan->where('jenis_potongan', 'like', '%'.$jenis_potongan.'%');

    }
    if($besar_potongan)
    {
        $potongan->where('besar_potongan', 'like', '%'.$besar_potongan.'%');
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

            // Create Potongan
            $potongan = Potongan::create([
                'jenis_potongan' => $request-> jenis_potongan,
                'besar_potongan' => $request-> besar_potongan,
                'pegawai_id' => $request-> pegawai_id
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

                // Update potongan
                $potongan -> update([
                    'jenis_potongan' => $request-> jenis_potongan,
                    'besar_potongan' => $request-> besar_potongan,
                    'pegawai_id' => $request-> pegawai_id
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

            // Delete Data Potongan
            $potongan->delete();

            return ResponseFormatter::success('Data Potongan Pegawai deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    }


