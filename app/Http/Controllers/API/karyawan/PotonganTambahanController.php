<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use App\Models\karyawan\Karyawan_Potongan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\karyawan\CreatePotonganTambahanRequest;
use App\Http\Requests\karyawan\UpdatePotonganTambahanRequest;
use App\Models\karyawan\Karyawan_Potongan_Tambahan;

class PotonganTambahanController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $nama_potongan= $request->input('nama_potongan');
        $besar_potongan = $request->input('besar_potongan');
        $karyawan_potongan_id= $request->input('karyawan_potongan_id');
        $month = $request->input('month');
        $year = $request->input('year');
        $limit = $request->input('limit', 10);

        $potongantambahanQuery = Karyawan_Potongan_Tambahan::query();

           // Get single data
    if($id)
    {
        $potongantambahan= $potongantambahanQuery->find($id);

        if($potongantambahan){
            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Karyawan found');
        }
            return ResponseFormatter::error('Data Potongan Tambahan Karyawan not found', 404);
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
    if ($karyawan_potongan_id) {
        $potongantambahan->where('karyawan_potongan_id', $karyawan_potongan_id);
    }



    return ResponseFormatter::success(
        $potongantambahan->paginate($limit),
        'Data Potongan Tambahan Dosen Tetap Found'
    );
    }



    public function create(CreatePotonganTambahanRequest $request){
        try{
            // Create Potongan
            $potongantambahan = Karyawan_Potongan_Tambahan::create([
                'nama_potongan' => $request-> nama_potongan,
                'besar_potongan' => $request-> besar_potongan,
                'karyawan_potongan_id' => $request-> karyawan_potongan_id
            ]);
            $potongan = Karyawan_Potongan::findOrFail($request-> karyawan_potongan_id);
            $totalpotongan = $potongan->total_potongan + $request->besar_potongan;
            $potongan->update([
               'total_potongan' => $totalpotongan
           ]);
            // Memanggil Controller Hitung Pajak untuk update value rumus pajak
            $hitungPajakController = new HitungPajakController();
            $hitungPajakController->Hitung_Pajak_Pot($request,$potongantambahan->karyawan_potongan_id); // Memanggil method Hitung_Pajak

            if(!$potongantambahan){
                throw new Exception('Data Potongan Tambahan Karyawan not created');
            }
            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Karyawan created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

        public function update(UpdatePotonganTambahanRequest $request, $id)
        {
            try {

                // Get Potongan
                $potongantambahan = Karyawan_Potongan_Tambahan::find($id);

                // Check if potongan exists
                if(!$potongantambahan){
                    throw new Exception('Data Potongan Tambahan Karyawan not found');
                }

                // Update potongan
                $potongantambahan -> update([
                    'karyawan_potongan_id' => $request-> karyawan_potongan_id,
                    'nama_potongan' => $request-> nama_potongan,
                    'besar_potongan' => $request-> besar_potongan
            ]);

            $potongan = Karyawan_Potongan::findOrFail($request->karyawan_potongan_id);
            $besar_potongan = Karyawan_Potongan_Tambahan::where('karyawan_potongan_id', $request->karyawan_potongan_id)
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
             $hitungPajakController->Hitung_Pajak_Pot($request,$potongantambahan->karyawan_potongan_id); // Memanggil method Hitung_Pajak

            return ResponseFormatter::success($potongantambahan, 'Data Potongan Tambahan Karyawan updated');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

    public function destroy(Request $request,$id){
        try{
            // Get Data Potongan
            $potongantambahan = Karyawan_Potongan_Tambahan::find($id);

            // Check if Potongan exists
            if(!$potongantambahan){
                throw new Exception('Data Potongan Tambahan Karyawan not found');
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

            return ResponseFormatter::success('Data Potongan Tambahan Karyawan deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    }
