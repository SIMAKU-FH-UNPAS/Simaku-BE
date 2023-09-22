<?php

namespace App\Http\Controllers\API\dosenlb;

use Exception;
use App\Models\dosenlb\Doslb_Potongan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\dosenlb\CreatePotonganTambahanRequest;
use App\Http\Requests\dosenlb\UpdatePotonganTambahanRequest;
use App\Models\dosenlb\Doslb_Potongan_Tambahan;

class PotonganTambahanController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $nama_potongan= $request->input('nama_potongan');
        $besar_potongan = $request->input('besar_potongan');
        $month = $request->input('month');
        $year = $request->input('year');
        $doslb_potongan_id= $request->input('doslb_potongan_id');
        $limit = $request->input('limit', 10);

        $potongantambahanQuery = Doslb_Potongan_Tambahan::query();

           // Get single data
    if($id)
    {
        $potongantambahan= $potongantambahanQuery->find($id);

        if($potongantambahan){
            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Dosen Luar Biasa found');
        }
            return ResponseFormatter::error('Data Potongan Tambahan Dosen Luar Biasa not found', 404);
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
    if($month && $year){
        $potongantambahan->whereMonth('created_at', $month)
        ->whereYear('created_at', $year);
    }
    if ($doslb_potongan_id) {
        $potongantambahan->where('doslb_potongan_id', $doslb_potongan_id);
    }



    return ResponseFormatter::success(
        $potongantambahan->paginate($limit),
        'Data Potongan Tambahan Dosen Luar Biasa Found'
    );
    }



    public function create(CreatePotonganTambahanRequest $request){
        try{
            // Create Potongan
            $potongantambahan = Doslb_Potongan_Tambahan::create([
                'nama_potongan' => $request-> nama_potongan,
                'besar_potongan' => $request-> besar_potongan,
                'doslb_potongan_id' => $request-> doslb_potongan_id
            ]);
            $potongan = Doslb_Potongan::findOrFail($request-> doslb_potongan_id);
            $totalpotongan = $potongan->total_potongan + $request->besar_potongan;
            $potongan->update([
               'total_potongan' => $totalpotongan
           ]);
            // Memanggil Controller Hitung Pajak untuk update value rumus pajak
            $hitungPajakController = new HitungPajakController();
            $hitungPajakController->Hitung_Pajak_Pot($request,$potongantambahan->doslb_potongan_id); // Memanggil method Hitung_Pajak

            if(!$potongantambahan){
                throw new Exception('Data Potongan Tambahan Dosen Luar Biasa not created');
            }
            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Dosen Luar Biasa created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

        public function update(UpdatePotonganTambahanRequest $request, $id)
        {
            try {

                // Get Potongan
                $potongantambahan = Doslb_Potongan_Tambahan::find($id);

                // Check if potongan exists
                if(!$potongantambahan){
                    throw new Exception('Data Potongan Tambahan Dosen Luar Biasa not found');
                }

                // Update potongan
                $potongantambahan -> update([
                    'doslb_potongan_id' => $request-> doslb_potongan_id,
                    'nama_potongan' => $request-> nama_potongan,
                    'besar_potongan' => $request-> besar_potongan
            ]);

            $potongan = Doslb_Potongan::findOrFail($request->doslb_potongan_id);
            $besar_potongan = Doslb_Potongan_Tambahan::where('doslb_potongan_id', $request->doslb_potongan_id)
            ->sum('besar_potongan');
            $totalpotongan =
            $potongan->sp_FH +
            $potongan->infaq +
            $besar_potongan;

            $potongan->update([
               'total_potongan' => $totalpotongan
           ]);
             // Memanggil Controller Hitung Pajak untuk update value rumus pajak
             $hitungPajakController = new HitungPajakController();
             $hitungPajakController->Hitung_Pajak_Pot($request,$potongantambahan->doslb_potongan_id); // Memanggil method Hitung_Pajak

            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Dosen Luar Biasa updated');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

    public function destroy(Request $request,$id){
        try{
            // Get Data Potongan
            $potongantambahan = Doslb_Potongan_Tambahan::find($id);

            // Check if Potongan exists
            if(!$potongantambahan){
                throw new Exception('Data Potongan Tambahan Dosen Luar Biasa not found');
            }

            $potongan = $potongantambahan->potongan;
            $totalpotongan =  $potongan->total_potongan - $potongantambahan->besar_potongan;
               $potongan->update([
                'total_potongan' => $totalpotongan
            ]);
              // Memanggil Controller Hitung Pajak untuk update value rumus pajak
              $hitungPajakController = new HitungPajakController();
              $hitungPajakController->Hitung_Pajak_Pot($request,$potongan->id); // Memanggil method Hitung_Pajak
            // Delete Data Potongan
            $potongantambahan->delete();

            return ResponseFormatter::success('Data Potongan Tambahan Dosen Luar Biasa deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    }
