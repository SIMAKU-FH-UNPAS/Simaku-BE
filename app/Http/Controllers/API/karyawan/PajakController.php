<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use App\Models\karyawan\Karyawan_Pajak;
use App\Models\karyawan\Karyawan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\karyawan\CreatePajakRequest;
use App\Http\Requests\karyawan\UpdatePajakRequest;

class PajakController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $pensiun = $request->input('pensiun');
        $bruto_pajak = $request->input('bruto_pajak');
        $bruto_murni = $request->input('bruto_murni');
        $biaya_jabatan = $request->input('biaya_jabatan');
        $aksa_mandiri = $request->input('aksa_mandiri');
        $dplk_pensiun = $request->input('dplk_pensiun');
        $jml_pot_kn_pajak = $request->input('jml_pot_kn_pajak');
        $jml_set_pot_kn_pajak = $request-> input('jml_set_pot_kn_pajak');
        $ptkp = $request->input('ptkp');
        $pkp = $request->input('pkp');
        $pajak_pph21 = $request->input('pajak_pph21');
        $jml_set_pajak = $request->input('jml_set_pajak');
        $pot_tk_kena_pajak = $request->input('pot_tk_kena_pajak');
        $pendapatan_bersih= $request->input('pendapatan_bersih');
        $month = $request->input('month');
        $year = $request->input('year');
        $karyawan_id= $request->input('karyawan_id');
        $karyawan_gaji_universitas_id= $request->input('karyawan_gaji_universitas_id');
        $karyawan_gaji_fakultas_id = $request->input('karyawan_gaji_fakultas_id');
        $karyawan_potongan_id = $request->input('karyawan_potongan_id');
        $limit = $request->input('limit', 10);

        $PajakQuery = Karyawan_Pajak::query();

    // Get single data
    if($id)
    {
        $pajak=  $PajakQuery->find($id);

        if($pajak){
            return ResponseFormatter::success($pajak, 'Data Pajak Karyawan found');
        }
            return ResponseFormatter::error('Data Pajak Karyawan not found', 404);
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
    if($aksa_mandiri)
    {
        $pajak->where('aksa_mandiri', 'like', '%'.$aksa_mandiri.'%');

    }
    if($dplk_pensiun)
    {
        $pajak->where('dplk_pensiun', 'like', '%'.$dplk_pensiun.'%');

    }
    if($jml_pot_kn_pajak)
    {
        $pajak->where('jml_pot_kn_pajak', 'like', '%'.$jml_pot_kn_pajak.'%');

    }
    if($jml_set_pot_kn_pajak)
    {
        $pajak->where('jml_set_pot_kn_pajak', 'like', '%'.$jml_set_pot_kn_pajak.'%');

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
    if($pendapatan_bersih)
    {
        $pajak->where('pendapatan_bersih', 'like', '%'.$pendapatan_bersih.'%');

    }
    if($month && $year){
        $pajak->whereMonth('created_at', $month)
        ->whereYear('created_at', $year);
    }
    if ($karyawan_id) {
        $pajak->where('karyawan_id', $karyawan_id);
    }
    if ($karyawan_gaji_universitas_id) {
        $pajak->where('karyawan_gaji_universitas_id', $karyawan_gaji_universitas_id);
    }
    if ($karyawan_gaji_fakultas_id) {
        $pajak->where('karyawan_gaji_fakultas_id', $karyawan_gaji_fakultas_id);
    }
    if ($karyawan_potongan_id) {
        $pajak->where('karyawan_potongan_id', $karyawan_potongan_id);
    }



    return ResponseFormatter::success(
        $pajak->paginate($limit),
        'Data Pajak Karyawan Found'
    );
}
public function create(CreatePajakRequest $request){
    try{
        $karyawan_id = $request->karyawan_id;

        // Get the current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;


        // Check if data already exists for the current month and year
        $existingpajak= Karyawan_Pajak::where('karyawan_id', $karyawan_id)
        ->whereMonth('created_at', $currentMonth)
        ->whereYear('created_at', $currentYear)
        ->first();

    if ($existingpajak) {
        return ResponseFormatter::error('Data Pajak Karyawan for this month already exists', 400);
    }
        // Get Data Pegawai
        $karyawan = Karyawan::findOrFail($request->karyawan_id);

        if ($karyawan->gaji_universitas->isNotEmpty() && $karyawan->gaji_fakultas->isNotEmpty() && $karyawan->potongan->isNotEmpty()){
        // Rumus Perhitungan menggunakan metode pada model Pajak
        $pajak = new Karyawan_Pajak;
        $bruto_pajak = $pajak->hitung_bruto_pajak($request);
        $bruto_murni = $pajak->hitung_bruto_murni($request);
        $biaya_jabatan = $pajak->hitung_biaya_jabatan();
        $jml_pot_kn_pajak = $pajak->hitung_jml_pot_kn_pajak($request);
        $jml_set_pot_kn_pajak = $pajak->hitung_jml_set_pot_kn_pajak();
        $pkp = $pajak->hitung_pkp($request);
        $pajak_pph21 = $pajak->hitung_pajak_pph21($request);
        $jml_set_pajak = $pajak->hitung_jml_set_pajak($request);
        $pot_tk_kena_pajak = $pajak->hitung_pot_tk_kena_pajak($request);
        $pendapatan_bersih = $pajak->hitung_pendapatan_bersih();

        // Create Pajak Pegawai
        $pajakkaryawan = Karyawan_Pajak::create([
            'pensiun' => $request-> pensiun,
            'bruto_pajak' => $bruto_pajak,
            'bruto_murni' => $bruto_murni,
            'biaya_jabatan' => $biaya_jabatan,
            'aksa_mandiri' => $request-> aksa_mandiri,
            'dplk_pensiun' => $request-> dplk_pensiun,
            'jml_pot_kn_pajak' => $jml_pot_kn_pajak,
            'jml_set_pot_kn_pajak' => $jml_set_pot_kn_pajak,
            'ptkp' => $request-> ptkp,
            'pkp' => $pkp,
            'pajak_pph21' => $pajak_pph21,
            'jml_set_pajak' => $jml_set_pajak,
            'pot_tk_kena_pajak' => $pot_tk_kena_pajak,
            'pendapatan_bersih' => $pendapatan_bersih,
            'karyawan_id' => $karyawan->id,
            'karyawan_gaji_universitas_id'=>$request->karyawan_gaji_universitas_id,
            'karyawan_gaji_fakultas_id'=>$request->karyawan_gaji_fakultas_id,
            'karyawan_potongan_id'=>$request->karyawan_potongan_id
        ]);
        return ResponseFormatter::success($pajakkaryawan, 'Data Pajak Karyawan created');
    }else{
         return ResponseFormatter::error('Gaji Universitas or Gaji Fakultas or Potongan is empty ');
    }
    throw new Exception('Data Pajak Karyawan not created');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }

    public function update(UpdatePajakRequest $request, $id)
    {
        try {

            // Get Pajak Pegawai
            $pajakkaryawan = Karyawan_Pajak::find($id);

            // Check if Pajak Pegawai exists
            if(!$pajakkaryawan){
                throw new Exception('Data Pajak Karyawan not found');
            }
        // Rumus Perhitungan menggunakan metode pada model Pajak
        $pajak = new Karyawan_Pajak;
        $bruto_pajak = $pajak->hitung_bruto_pajak($request);
        $bruto_murni = $pajak->hitung_bruto_murni($request);
        $biaya_jabatan = $pajak->hitung_biaya_jabatan();
        $jml_pot_kn_pajak = $pajak->hitung_jml_pot_kn_pajak($request);
        $jml_set_pot_kn_pajak = $pajak->hitung_jml_set_pot_kn_pajak();
        $pkp = $pajak->hitung_pkp($request);
        $pajak_pph21 = $pajak->hitung_pajak_pph21($request);
        $jml_set_pajak = $pajak->hitung_jml_set_pajak($request);
        $pot_tk_kena_pajak = $pajak->hitung_pot_tk_kena_pajak($request);
        $pendapatan_bersih = $pajak->hitung_pendapatan_bersih();

            //  // Mengecek apakah ada data besar_pajak dari tabel Pajak_Tambahan
            //  $pajak_tambahan = Pajak_Tambahan::where('pajak_id',$id)->whereNull('deleted_at')->get();
            //  if ($pajak_tambahan){
            //      foreach($pajak_tambahan as $pajak){
            //          // Jika ada, tambahkan besar_pajak ke total_pajak
            //      $total_pajak += $pajak->besar_pajak;
            //  }
            // }

            // Update Pajak Pegawai
            $pajakkaryawan -> update([
                'pensiun' => $request-> pensiun,
                'bruto_pajak' => $bruto_pajak,
                'bruto_murni' => $bruto_murni,
                'biaya_jabatan' => $biaya_jabatan,
                'aksa_mandiri' => $request-> aksa_mandiri,
                'dplk_pensiun' => $request-> dplk_pensiun,
                'jml_pot_kn_pajak' => $jml_pot_kn_pajak,
                'jml_set_pot_kn_pajak' => $jml_set_pot_kn_pajak,
                'ptkp' => $request-> ptkp,
                'pkp' => $pkp,
                'pajak_pph21' => $pajak_pph21,
                'jml_set_pajak' => $jml_set_pajak,
                'pot_tk_kena_pajak' => $pot_tk_kena_pajak,
                'pendapatan_bersih' => $pendapatan_bersih,
                'karyawan_id' => $request->karyawan_id,
                'karyawan_gaji_universitas_id'=>$request->karyawan_gaji_universitas_id,
                'karyawan_gaji_fakultas_id'=>$request->karyawan_gaji_fakultas_id,
                'karyawan_potongan_id'=>$request->karyawan_potongan_id

        ]);


        return ResponseFormatter::success($pajakkaryawan, 'Data Pajak Karyawan updated');
    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
    }
    public function destroy($id){
        try{
            // Get Data Pajak Pegawai
            $pajakkaryawan = Karyawan_Pajak::findorFail($id);

            // Check if Data Pajak Pegawai exists
            if(!$pajakkaryawan){
                throw new Exception('Data Pajak Karyawan not found');
            }

            // Delete the related Pajak_Tambahan records
            $pajakkaryawan->pajaktambahan()->delete();
            // Delete Data Pajak Pegawai
            $pajakkaryawan->delete();

            return ResponseFormatter::success('Data Pajak Karyawan deleted');

        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
