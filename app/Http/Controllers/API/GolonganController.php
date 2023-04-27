<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGolonganRequest;
use App\Http\Requests\UpdateGolonganRequest;
use App\Models\Golongan;

class GolonganController extends Controller
{

    public function fetch(Request $request ){
        $id = $request->input('id');
        $jenis_golongan = $request->input('jenis_golongan');
        $limit = $request->input('limit', 10);

        $golonganQuery = Golongan::query();


     // Get single data
    if($id)
    {
        $golongan= $golonganQuery->find($id);

        if($golongan){
            return ResponseFormatter::success($golongan, 'Data Golongan Pegawai found');
        }
            return ResponseFormatter::error('Data Golongan Pegawai not found', 404);
    }

    //    Get multiple Data
    $golongan = $golonganQuery;

    // Get by attribute
    if($golongan)
    {
        $golongan->where('jenis_golongan', 'like', '%'.$jenis_golongan.'%');

    }


    return ResponseFormatter::success(
        $golongan->paginate($limit),
        'Data Folongan Pegawai Found'
    );
    }



    public function create(CreateGolonganRequest $request){
       try {

           // Create Dosen Luar Biasa
        $golongan = Golongan::create([
            'jenis_golongan' => $request-> jenis_golongan,

        ]);

        if(!$golongan){
            throw new Exception('Data Golongan Pegawai not created');
        }
        return ResponseFormatter::success($golongan, 'Data Golongan Pegawai created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}

    public function update(UpdateGolonganRequest $request, $id)
    {
        try {

            // Get Dosen Luar Biasa
            $golongan = Golongan::find($id);

            // Check if Dosen Luar Biasa exists
            if(!$golongan){
                throw new Exception('Data Golongan Pegawai not found');
            }

            // Update Dosen Luar Biasa
            $golongan -> update([
                'jenis_golongan' => $request-> jenis_golongan,
        ]);


        return ResponseFormatter::success($golongan, 'Data Golongan Pegawai updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

public function destroy($id){
    try{
        // Get Data Dosen Luar Biasa
        $golongan = Golongan::find($id);

        // Check if Data Dosen Luar Biasa exists
        if(!$golongan){
            throw new Exception('Data Golongan Pegawai not found');
        }

        // Delete Data Dosen Luar Biasa
        $golongan->delete();

        return ResponseFormatter::success('Data Golongan Pegawai deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
}
}
