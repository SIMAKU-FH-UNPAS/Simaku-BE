<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Potongan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePotonganTambahanRequest;
use App\Http\Requests\UpdatePotonganTambahanRequest;
use App\Models\Potongan_Tambahan;

class PotonganTambahanController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $nama_potongan= $request->input('nama_potongan');
        $besar_potongan = $request->input('besar_potongan');
        $potongan_id= $request->input('potongan_id');
        $limit = $request->input('limit', 10);

        $potongantambahanQuery = Potongan_Tambahan::query();

           // Get single data
    if($id)
    {
        $potongantambahan= $potongantambahanQuery->find($id);

        if($potongantambahan){
            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Pegawai found');
        }
            return ResponseFormatter::error('Data Potongan Tambahan Pegawai not found', 404);
    }

    //    Get multiple Data
    $potongantambahan = $potongantambahanQuery;

    // // Get by attribute
    if($nama_potongan)
    {
        $potongantambahan->where('nama_potongan', 'like', '%'.$nama_potongan.'%');

    }
    if($besar_potongan)
    {
        $potongantambahan->where('besar_potongan', 'like', '%'.$besar_potongan.'%');
    }
    if ($potongan_id) {
        $potongantambahan->where('potongan_id', $potongan_id);
    }



    return ResponseFormatter::success(
        $potongantambahan->paginate($limit),
        'Data Potongan Tambahan Pegawai Found'
    );
    }



    public function create(CreatePotonganTambahanRequest $request, $potongan_id){
        try{
            $potongan = Potongan::findOrFail($potongan_id);
            // Create Potongan
            $potongantambahan = Potongan_Tambahan::create([
                'nama_potongan' => $request-> nama_potongan,
                'besar_potongan' => $request-> besar_potongan,
                'potongan_id' => $request-> potongan_id
            ]);
            $totalpotongan = $potongan->total_potongan + $request->besar_potongan;
            $potongan->update([
               'total_potongan' => $totalpotongan
           ]);

            if(!$potongantambahan){
                throw new Exception('Data Potongan Tambahan Pegawai not created');
            }
            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Pegawai created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

        public function update(UpdatePotonganTambahanRequest $request, $id, $potongan_id)
        {
            try {

                // Get Potongan
                $potongantambahan = Potongan_Tambahan::find($id);

                // Check if potongan exists
                if(!$potongantambahan){
                    throw new Exception('Data Potongan Tambahan Pegawai not found');
                }

                // Update potongan
                $potongantambahan -> update([
                    'potongan_id' => $request-> potongan_id,
                    'nama_potongan' => $request-> nama_potongan,
                    'besar_potongan' => $request-> besar_potongan
            ]);

            $potongan = Potongan::findOrFail($potongan_id);
            $totalpotongan =
            $potongan->sp_FH +
            $potongan->iiku +
            $potongan->iid +
            $potongan->infaq +
            $potongan->abt +
            $request->besar_potongan;

            $potongan->update([
               'total_potongan' => $totalpotongan
           ]);


            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Pegawai updated');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

    public function destroy($id){
        try{
            // Get Data Potongan
            $potongantambahan = Potongan_Tambahan::find($id);

            // Check if Potongan exists
            if(!$potongantambahan){
                throw new Exception('Data Potongan Tambahan Pegawai not found');
            }

            $potongan = $potongantambahan->potongan;
            $totalpotongan =  $potongan->total_potongan - $potongantambahan->besar_potongan;
               $potongan->update([
                'total_potongan' => $totalpotongan
            ]);

            // Delete Data Potongan
            $potongantambahan->delete();

            return ResponseFormatter::success('Data Potongan Tambahan Pegawai deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    }


