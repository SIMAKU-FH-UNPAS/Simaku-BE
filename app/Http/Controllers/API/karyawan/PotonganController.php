<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use App\Models\karyawan\Karyawan_Potongan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\karyawan\CreatePotonganRequest;
use App\Http\Requests\karyawan\UpdatePotonganRequest;
use App\Models\karyawan\Karyawan_Potongan_Tambahan;

class PotonganController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $karyawan_id= $request->input('karyawan_id');
        $sp_FH= $request->input('sp_FH');
        $infaq= $request->input('infaq');
        $total_potongan= $request->input('total_potongan');
        $limit = $request->input('limit', 10);

        $potonganQuery = Karyawan_Potongan::query();

           // Get single data
    if($id)
    {
        $potongan= $potonganQuery->find($id);

        if($potongan){
            return ResponseFormatter::success($potongan, 'Data Potongan Karyawan found');
        }
            return ResponseFormatter::error('Data Potongan Karyawan not found', 404);
    }

    //    Get multiple Data
    $potongan = $potonganQuery;

    // // Get by attribute
    if($sp_FH)
    {
        $potongan->where('sp_FH', 'like', '%'.$sp_FH.'%');

    }
    if($infaq)
    {
        $potongan->where('infaq', 'like', '%'.$infaq.'%');
    }
    if($total_potongan)
    {
        $potongan->where('total_potongan', 'like', '%'.$total_potongan.'%');

    }
    if ($karyawan_id) {
        $potongan->where('karyawan_id', $karyawan_id);
    }



    return ResponseFormatter::success(
        $potongan->paginate($limit),
        'Data Potongan Karyawan Found'
    );
    }



    public function create(CreatePotonganRequest $request){
        try{
            $karyawan_id = $request->karyawan_id;

            // Get the current month and year
            $currentMonth = now()->month;
            $currentYear = now()->year;


            // Check if data already exists for the current month and year
            $existingpotongan = Karyawan_Potongan::where('karyawan_id', $karyawan_id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->first();

        if ($existingpotongan) {
            return ResponseFormatter::error('Data Potongan Karyawan for this month already exists', 400);
        }

            $potonganobjek = new Karyawan_Potongan;
            $total_potongan = $potonganobjek->total_potongan($request);

            // Create Potongan
            $potongan = Karyawan_Potongan::create([
                'karyawan_id' => $request-> karyawan_id,
                'sp_FH' => $request-> sp_FH,
                'infaq' => $request-> infaq,
                'total_potongan' => $total_potongan
            ]);
            if(!$potongan){
                throw new Exception('Data Potongan Karyawan not created');
            }
            return ResponseFormatter::success($potongan, 'Data Potongan Karyawan created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

        public function update(UpdatePotonganRequest $request, $id)
        {
            try {

                // Get Potongan
                $potongan = Karyawan_Potongan::find($id);

                // Check if potongan exists
                if(!$potongan){
                    throw new Exception('Data Potongan Karyawan not found');
                }

                // Menghitung total_potongan tanpa mempertimbangkan besar_potongan
                $total_potongan =
                $request-> sp_FH +
                $request-> infaq;

                // Mengecek apakah ada data besar_potongan dari tabel Potongan_Tambahan
                $potongan_tambahan = Karyawan_Potongan_Tambahan::where('karyawan_potongan_id',$id)->whereNull('deleted_at')->get();
                if ($potongan_tambahan){
                    foreach($potongan_tambahan as $potongantambahan){
                        // Jika ada, tambahkan besar_potongan ke total_potongan
                    $total_potongan += $potongantambahan->besar_potongan;
                }
                    }

                // Update potongan
                $potongan -> update([
                    'karyawan_id' => $request-> karyawan_id,
                    'sp_FH' => $request-> sp_FH,
                    'infaq' => $request-> infaq,
                    'total_potongan' => $total_potongan
            ]);
              // Memanggil Controller Hitung Pajak untuk update value rumus pajak
           $hitungPajakController = new HitungPajakController();
           $hitungPajakController->Hitung_Pajak_Pot($request,$potongan->id); // Memanggil method Hitung_Pajak

            return ResponseFormatter::success($potongan, 'Data Potongan Karyawan updated');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
        }

    public function destroy($id){
        try{
            // Get Data Potongan
            $potongan = Karyawan_Potongan::find($id);

            // Check if Potongan exists
            if(!$potongan){
                throw new Exception('Data Potongan Karyawan not found');
            }

             // Delete the related Honor_Fakultas_Tambahan records
              $potongan->potongantambahan()->delete();

            // Delete Data Potongan
            $potongan->delete();

            return ResponseFormatter::success('Data Potongan Karyawan deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    }
