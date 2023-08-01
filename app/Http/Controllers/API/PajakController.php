<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Pajak;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePajakRequest;
use App\Http\Requests\UpdatePajakRequest;
use App\Models\Pajak_Tambahan;

class PajakController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $pegawai_id= $request->input('pegawai_id');
        $pensiun = $request->input('pensiun');
        $bruto_pajak = $request->input('bruto_pajak');
        $bruto_murni = $request->input('bruto_murni');
        $biaya_jabatan = $request->input('biaya_jabatan');
        $as_bumi_putera = $request->input('as_bumi_putera');
        $dplk_pensiun = $request->input('dplk_pensiun');
        $jml_pot_kn_pajak = $request->input('jml_pot_kn_pajak');
        $set_potongan_kn_pajak = $request-> input('set_potongan_kn_pajak');
        $ptkp = $request->input('ptkp');
        $pkp = $request->input('pkp');
        $pajak_pph21 = $request->input('pajak_pph21');
        $jml_set_pajak = $request->input('jml_set_pajak');
        $pot_tk_kena_pajak = $request->input('pot_tk_kena_pajak');
        $total_pajak= $request->input('total_pajak');
        $limit = $request->input('limit', 10);

        $PajakQuery = Pajak::query();

    // Get single data
    if($id)
    {
        $pajak=  $PajakQuery->find($id);

        if($pajak){
            return ResponseFormatter::success($pajak, 'Data Pajak Pegawai found');
        }
            return ResponseFormatter::error('Data Pajak Pegawai not found', 404);
    }

    //    Get multiple Data
    $pajak = $PajakQuery;

    // // Get by attribute
    if($pensiun )
    {
        $pajak->where('pensiun', 'like', '%'.$pensiun.'%');

    }
    if($bruto_pajak)
    {
        $pajak->where('bruto_pajak', 'like', '%'.$bruto_pajak.'%');

    }
    if($bruto_murni)
    {
        $pajak->where('bruto_murni', 'like', '%'.$bruto_murni.'%');

    }
    if($biaya_jabatan)
    {
        $pajak->where('biaya_jabatan', 'like', '%'.$biaya_jabatan.'%');

    }
    if($as_bumi_putera)
    {
        $pajak->where('as_bumi_putera', 'like', '%'.$as_bumi_putera.'%');

    }
    if($dplk_pensiun)
    {
        $pajak->where('dplk_pensiun', 'like', '%'.$dplk_pensiun.'%');

    }
    if($jml_pot_kn_pajak)
    {
        $pajak->where('jml_pot_kn_pajak', 'like', '%'.$jml_pot_kn_pajak.'%');

    }
    if($set_potongan_kn_pajak)
    {
        $pajak->where('set_potongan_kn_pajak', 'like', '%'.$set_potongan_kn_pajak.'%');

    }
    if($jml_pot_kn_pajak)
    {
        $pajak->where('jml_pot_kn_pajak', 'like', '%'.$jml_pot_kn_pajak.'%');

    }
    if($ptkp)
    {
        $pajak->where('ptkp', 'like', '%'. $ptkp.'%');

    }
    if($pkp)
    {
        $pajak->where('pkp', 'like', '%'.$pkp.'%');

    }
    if($pajak_pph21)
    {
        $pajak->where('pajak_pph21', 'like', '%'.$pajak_pph21.'%');

    }
    if($jml_set_pajak)
    {
        $pajak->where('jml_set_pajak', 'like', '%'.$jml_set_pajak.'%');

    }
    if($pot_tk_kena_pajak)
    {
        $pajak->where('pot_tk_kena_pajak', 'like', '%'.$pot_tk_kena_pajak.'%');

    }
    if($total_pajak)
    {
        $pajak->where('total_pajak', 'like', '%'.$total_pajak.'%');

    }
    if ($pegawai_id) {
        $pajak->where('pegawai_id', $pegawai_id);
    }



    return ResponseFormatter::success(
        $pajak->paginate($limit),
        'Data Pajak Pegawai Found'
    );
}
public function create(CreatePajakRequest $request){
    try{
        $pajak = new Pajak;
        $total_pajak = $pajak->total_pajak($request);
        // Create Pajak Pegawai
        $pajakpegawai = Pajak::create([
            'pensiun' => $request-> pensiun,
            'bruto_pajak' => $request-> bruto_pajak,
            'bruto_murni' => $request-> bruto_murni,
            'biaya_jabatan' => $request-> biaya_jabatan,
            'as_bumi_putera' => $request-> as_bumi_putera,
            'dplk_pensiun' => $request-> dplk_pensiun,
            'jml_pot_kn_pajak' => $request-> jml_pot_kn_pajak,
            'set_potongan_kn_pajak' => $request-> set_potongan_kn_pajak,
            'ptkp' => $request-> ptkp,
            'pkp' => $request-> pkp,
            'pajak_pph21' => $request-> pajak_pph21,
            'jml_set_pajak' => $request-> jml_set_pajak,
            'pot_tk_kena_pajak' => $request-> pot_tk_kena_pajak,
            'total_pajak' => $total_pajak,
            'pegawai_id' => $request-> pegawai_id,
        ]);
        if(!$pajakpegawai){
            throw new Exception('Data Pajak Pegawai not created');
        }
        return ResponseFormatter::success($pajakpegawai, 'Data Pajak Pegawai created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

    public function update(UpdatePajakRequest $request, $id)
    {
        try {

            // Get Pajak Pegawai
            $pajakpegawai = Pajak::find($id);

            // Check if Pajak Pegawai exists
            if(!$pajakpegawai){
                throw new Exception('Data Pajak Pegawai not found');
            }

            // Menghitung total_pajak tanpa mempertimbangkan besar_pajak
            $total_pajak =
            $request->pensiun +
            $request->bruto_pajak +
            $request->bruto_murni +
            $request->biaya_jabatan +
            $request->as_bumi_putera +
            $request->dplk_pensiun +
            $request->jml_pot_kn_pajak +
            $request->set_potongan_kn_pajak +
            $request->ptkp +
            $request->pkp +
            $request->pajak_pph21 +
            $request->jml_set_pajak +
            $request->pot_tk_kena_pajak;

             // Mengecek apakah ada data besar_pajak dari tabel Pajak_Tambahan
             $pajak_tambahan = Pajak_Tambahan::where('pajak_id',$id)->whereNull('deleted_at')->get();
             if ($pajak_tambahan){
                 foreach($pajak_tambahan as $pajak){
                     // Jika ada, tambahkan besar_pajak ke total_pajak
                 $total_pajak += $pajak->besar_pajak;
             }
            }
            // Update Pajak Pegawai
            $pajakpegawai -> update([
            'pegawai_id' => $request-> pegawai_id,
            'pensiun' => $request-> pensiun,
            'bruto_pajak' => $request-> bruto_pajak,
            'bruto_murni' => $request-> bruto_murni,
            'biaya_jabatan' => $request-> biaya_jabatan,
            'as_bumi_putera' => $request-> as_bumi_putera,
            'dplk_pensiun' => $request-> dplk_pensiun,
            'jml_pot_kn_pajak' => $request-> jml_pot_kn_pajak,
            'set_potongan_kn_pajak' => $request-> set_potongan_kn_pajak,
            'ptkp' => $request-> ptkp,
            'pkp' => $request-> pkp,
            'pajak_pph21' => $request-> pajak_pph21,
            'jml_set_pajak' => $request-> jml_set_pajak,
            'pot_tk_kena_pajak' => $request-> pot_tk_kena_pajak,
            'total_pajak' => $total_pajak
        ]);


        return ResponseFormatter::success($pajakpegawai, 'Data Pajak Pegawai updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }
    public function destroy($id){
        try{
            // Get Data Pajak Pegawai
            $pajakpegawai = Pajak::findorFail($id);

            // Check if Data Pajak Pegawai exists
            if(!$pajakpegawai){
                throw new Exception('Data Pajak Pegawai not found');
            }

            // Delete the related Pajak_Tambahan records
            $pajakpegawai->pajaktambahan()->delete();
            // Delete Data Pajak Pegawai
            $pajakpegawai->delete();

            return ResponseFormatter::success('Data Pajak Pegawai deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
