<?php

namespace App\Http\Controllers\API\karyawan;

use Exception;
use Carbon\Carbon;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\karyawan\CreateGajiFakRequest;
use App\Http\Requests\karyawan\CreateGajiUnivRequest;
use App\Http\Requests\karyawan\CreateMasterTransaksiRequest;
use App\Http\Requests\karyawan\CreatePajakRequest;
use App\Http\Requests\karyawan\CreatePotonganRequest;
use App\Http\Requests\karyawan\UpdateGajiFakRequest;
use App\Http\Requests\karyawan\UpdateGajiUnivRequest;
use App\Http\Requests\karyawan\UpdateMasterTransaksiRequest;
use App\Http\Requests\karyawan\UpdatePajakRequest;
use App\Http\Requests\karyawan\UpdatePotonganRequest;
use App\Models\karyawan\Karyawan;
use App\Models\karyawan\Karyawan_Gaji_Fakultas;
use App\Models\karyawan\Karyawan_Gaji_Universitas;
use App\Models\karyawan\Karyawan_Master_Transaksi;
use App\Models\karyawan\Karyawan_Pajak;
use App\Models\karyawan\Karyawan_Potongan;
use Illuminate\Support\Facades\DB;


class TransaksiGajiController extends Controller
{
   // Ambil Data Transaksi Gaji sesuai id karyawan
   public function fetch($karyawanId)
{
    // Get Karyawan
    $karyawan = Karyawan::find($karyawanId);

    if (!$karyawan) {
        return ResponseFormatter::error(null, 'Karyawan Not Found', 404);
    }

    // Inisialisasi data karyawan
    $transaksi = [
        'karyawan_id' => $karyawan->id,
        'no_pegawai' => $karyawan->no_pegawai,
        'nama' => $karyawan->nama,
        'npwp' => $karyawan->npwp,
        'golongan' => $karyawan->golongan,
        'jabatan' => $karyawan->jabatan,
        'nomor_hp' => $karyawan->nomor_hp,
        'transaksi' => [],
    ];

    // Get ALL DATA Master Transaksi
    $transaksigaji = Karyawan_Master_Transaksi::where('karyawan_id', $karyawan->id)->get();

    foreach ($transaksigaji as $gaji) {
         // Get Periode
         $gaji_date_start = Carbon::createFromFormat('Y-m-d', $gaji->gaji_date_start);
        $gaji_date_end = Carbon::createFromFormat('Y-m-d', $gaji->gaji_date_end);

        $bankMasterTransaksi = Karyawan_Master_Transaksi::with(['bank'])
            ->where('karyawan_id', $karyawan->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        $gajiunivMasterTransaksi = Karyawan_Master_Transaksi::with(['gaji_universitas'])
            ->where('karyawan_id', $karyawan->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        $gajifakMasterTransaksi = Karyawan_Master_Transaksi::with('gaji_fakultas')
            ->where('karyawan_id', $karyawan->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();
            $gajifakMasterTransaksi->transform(function ($item) {
            $item->gaji_fakultas->gaji_fakultas = json_decode($item->gaji_fakultas->gaji_fakultas);
            return $item;
        });

        $potonganMasterTransaksi = Karyawan_Master_Transaksi::with('potongan')
            ->where('karyawan_id', $karyawan->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();
        $potonganMasterTransaksi->transform(function ($item) {
            $item->potongan->potongan = json_decode($item->potongan->potongan);
            return $item;
        });

        $pajakMasterTransaksi = Karyawan_Master_Transaksi::with(['pajak'])
            ->where('karyawan_id', $karyawan->id)
            ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
            ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
            ->get();

        // Transformasi data transaksi
        $transaksiData = [
            'id'=>$gaji->id,
            'periode' => [
                'month' => $gaji_date_end->format('F'),
                'year' => $gaji_date_end->format('Y'),
            ],
            'bank' => $bankMasterTransaksi->pluck('bank')->toArray(),
            'status_bank'=> $gaji->status_bank,
            'gaji_universitas' => $gajiunivMasterTransaksi->pluck('gaji_universitas')->toArray(),
            'gaji_fakultas' => $gajifakMasterTransaksi->pluck('gaji_fakultas')->toArray(),
            'potongan' => $potonganMasterTransaksi->pluck('potongan')->toArray(),
            'pajak' => $pajakMasterTransaksi->pluck('pajak')->toArray()
        ];

        // Menambahkan data transaksi ke dalam array transaksi utama
        $transaksi['transaksi'][] = $transaksiData;
    }

    return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Karyawan Found');

}

// Ambil Data Transaksi Gaji sesuai id transaksi
public function fetchById($transaksiId)
{
    // Get Master Transaksi
    $masterTransaksi = Karyawan_Master_Transaksi::find($transaksiId);

    if (!$masterTransaksi) {
        return ResponseFormatter::error('Master Transaksi Not Found', 404);
    }

    // Get Periode
    $gaji_date_start = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_start);
    $gaji_date_end = Carbon::createFromFormat('Y-m-d', $masterTransaksi->gaji_date_end);

    // Get Karyawan
    $karyawan = Karyawan::with('banks')->find($masterTransaksi->karyawan_id);
    if (!$karyawan) {
        return ResponseFormatter::error(null, 'Karyawan Not Found', 404);
    }

    // Inisialisasi data karyawan
        $transaksi = [
            'karyawan_id' => $karyawan->id,
            'no_pegawai' => $karyawan->no_pegawai,
            'nama' => $karyawan->nama,
            'npwp' => $karyawan->npwp,
            'golongan' => $karyawan->golongan,
            'jabatan' => $karyawan->jabatan,
            'nomor_hp' => $karyawan->nomor_hp,
            'banks' => $karyawan->banks,
            'transaksi' => [],
        ];

    $bankMasterTransaksi = Karyawan_Master_Transaksi::with(['bank'])
        ->where('karyawan_id', $karyawan->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();

    $gajiunivMasterTransaksi = Karyawan_Master_Transaksi::with(['gaji_universitas'])
        ->where('karyawan_id', $karyawan->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();

    $gajifakMasterTransaksi = Karyawan_Master_Transaksi::with('gaji_fakultas')
        ->where('karyawan_id', $karyawan->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();
    $gajifakMasterTransaksi->transform(function ($item) {
        $item->gaji_fakultas->gaji_fakultas = json_decode($item->gaji_fakultas->gaji_fakultas);
        return $item;
    });

    $potonganMasterTransaksi = Karyawan_Master_Transaksi::with('potongan')
        ->where('karyawan_id', $karyawan->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();
    $potonganMasterTransaksi->transform(function ($item) {
        $item->potongan->potongan = json_decode($item->potongan->potongan);
        return $item;
    });

    $pajakMasterTransaksi = Karyawan_Master_Transaksi::with(['pajak'])
        ->where('karyawan_id', $karyawan->id)
        ->where('gaji_date_start', $gaji_date_start->format('Y-m-d'))
        ->where('gaji_date_end', $gaji_date_end->format('Y-m-d'))
        ->get();

    // Transformasi data transaksi
    $transaksiData = [
        'id' => $masterTransaksi->id,
        'gaji_date_start' => $gaji_date_start->format('d F Y'),
        'gaji_date_end' => $gaji_date_end->format('d F Y'),
        'bank' => $bankMasterTransaksi->pluck('bank')->toArray(),
        'status_bank'=> $masterTransaksi->status_bank,
        'gaji_universitas' => $gajiunivMasterTransaksi->pluck('gaji_universitas')->toArray(),
        'gaji_fakultas' => $gajifakMasterTransaksi->pluck('gaji_fakultas')->toArray(),
        'potongan' => $potonganMasterTransaksi->pluck('potongan')->toArray(),
        'pajak' => $pajakMasterTransaksi->pluck('pajak')->toArray()
    ];


        // Menambahkan data transaksi ke dalam array transaksi utama
        $transaksi['transaksi'][] = $transaksiData;

    return ResponseFormatter::success($transaksi, 'Data Transaksi Gaji Karyawan Found');
}

public function create(CreateGajiUnivRequest $gajiunivRequest, CreateGajiFakRequest $gajifakRequest, CreatePotonganRequest $potonganRequest, CreatePajakRequest $pajakRequest, CreateMasterTransaksiRequest $transaksiRequest){
    // Memulai transaksi database
    DB::beginTransaction();
   try{
     // Ambil periode dari request
     $gaji_date_end = Carbon::createFromFormat('Y-m-d', $transaksiRequest->gaji_date_end);

     // Cek apakah transaksi untuk periode tersebut sudah ada (bulan dan tahun terakhir sama)
     $existingTransaction = Karyawan_Master_Transaksi::whereYear('gaji_date_end', $gaji_date_end->year)
     ->whereMonth('gaji_date_end', $gaji_date_end->month)
     ->where('karyawan_id', $transaksiRequest->karyawan_id)
     ->exists();

     if ($existingTransaction) {
         throw new Exception('Transaksi gaji untuk periode bulan tersebut sudah ada.');
     }

       // Create Gaji Universitas
       $gajiuniv = Karyawan_Gaji_Universitas::create([
        'gaji_pokok' => $gajiunivRequest-> gaji_pokok,
        'tunjangan_fungsional' => $gajiunivRequest-> tunjangan_fungsional,
        'tunjangan_struktural' => $gajiunivRequest-> tunjangan_struktural,
        'tunjangan_khusus_istimewa' => $gajiunivRequest-> tunjangan_khusus_istimewa,
        'tunjangan_presensi_kerja' => $gajiunivRequest-> tunjangan_presensi_kerja,
        'tunjangan_tambahan' => $gajiunivRequest-> tunjangan_tambahan,
        'tunjangan_suami_istri' => $gajiunivRequest-> tunjangan_suami_istri,
        'tunjangan_anak' => $gajiunivRequest-> tunjangan_anak,
        'uang_lembur_hk' => $gajiunivRequest-> uang_lembur_hk,
        'uang_lembur_hl' => $gajiunivRequest-> uang_lembur_hl,
        'transport_kehadiran' => $gajiunivRequest-> transport_kehadiran,
        'honor_universitas' => $gajiunivRequest-> honor_universitas,
       ]);

       //Create Gaji Fakultas
       $gajifak = Karyawan_Gaji_Fakultas::create([
           'gaji_fakultas' => json_encode($gajifakRequest->gaji_fakultas)
       ]);
        // Decode "gaji_fakultas" data before sending the response
        $gajifak->gaji_fakultas = json_decode($gajifak->gaji_fakultas);

       //Create Potongan
       $potongan = Karyawan_Potongan::create([
           'potongan' => json_encode($potonganRequest->potongan)
       ]);
        // Decode "potongan" data before sending the response
        $potongan->potongan = json_decode($potongan->potongan);

       //Create Pajak
       $pajak = Karyawan_Pajak::create([
        'pensiun' => $pajakRequest-> pensiun,
        'bruto_pajak' =>  $pajakRequest->bruto_pajak,
        'bruto_murni' =>  $pajakRequest->bruto_murni,
        'biaya_jabatan' =>  $pajakRequest->biaya_jabatan,
        'aksa_mandiri' => $pajakRequest-> aksa_mandiri,
        'dplk_pensiun' => $pajakRequest-> dplk_pensiun,
        'jumlah_potongan_kena_pajak' =>  $pajakRequest->jumlah_potongan_kena_pajak,
        'jumlah_set_potongan_kena_pajak' =>  $pajakRequest->jumlah_set_potongan_kena_pajak,
        'ptkp' => $pajakRequest-> ptkp,
        'pkp' =>  $pajakRequest->pkp,
        'pajak_pph21' =>  $pajakRequest->pajak_pph21,
        'jumlah_set_pajak' =>  $pajakRequest->jumlah_set_pajak,
        'potongan_tak_kena_pajak' =>  $pajakRequest->potongan_tak_kena_pajak,
        'pendapatan_bersih' =>  $pajakRequest->pendapatan_bersih,
       ]);

       // Master Transaksi
       $mastertransaksi = Karyawan_Master_Transaksi::create([
       'karyawan_id'=> $transaksiRequest->karyawan_id,
       'karyawan_bank_id'=>  $transaksiRequest->karyawan_bank_id,
       'status_bank' => $transaksiRequest-> status_bank,
       'gaji_date_start' => $transaksiRequest-> gaji_date_start,
       'gaji_date_end' => $transaksiRequest-> gaji_date_end,
       'karyawan_gaji_universitas_id'=> $gajiuniv->id,
       'karyawan_gaji_fakultas_id'=> $gajifak->id,
       'karyawan_potongan_id'=>$potongan->id,
       'karyawan_pajak_id'=>$pajak->id
       ]);

   // Commit transaksi jika berhasil
    DB::commit();


   if(!$gajiuniv && !$gajifak && !$potongan && !$pajak){
       throw new Exception('Data Transaksi Gaji Karyawan not created');
   }

    return ResponseFormatter::success([
        'gaji_universitas' => $gajiuniv,
        'gaji_fakultas' => $gajifak,
        'potongan' => $potongan,
        'pajak' => $pajak,
        'transaksi' => $mastertransaksi,
    ],'Data Transaksi Gaji Karyawan created');
   }
   catch(Exception $e){
       DB::rollback();
       return ResponseFormatter::error($e->getMessage(), 500);
   }
}

// Update Transaksi Gaji Karyawan
public function update(UpdateGajiUnivRequest $gajiunivRequest, UpdateGajiFakRequest $gajifakRequest, UpdatePotonganRequest $potonganRequest, UpdatePajakRequest $pajakRequest, UpdateMasterTransaksiRequest $transaksiRequest, $transaksiId){
    // Memulai transaksi database
    DB::beginTransaction();
   try{
    // Get Master Transaksi
    $mastertransaksi = Karyawan_Master_Transaksi::find($transaksiId);
    if ($mastertransaksi) {
        // Update Gaji Universitas
        $gajiuniv = $mastertransaksi->gaji_universitas;
        if ($gajiuniv) {
        // Update Gaji Universitas
        $gajiuniv->update([
            'gaji_pokok' => $gajiunivRequest-> gaji_pokok,
            'tunjangan_fungsional' => $gajiunivRequest-> tunjangan_fungsional,
            'tunjangan_struktural' => $gajiunivRequest-> tunjangan_struktural,
            'tunjangan_khusus_istimewa' => $gajiunivRequest-> tunjangan_khusus_istimewa,
            'tunjangan_presensi_kerja' => $gajiunivRequest-> tunjangan_presensi_kerja,
            'tunjangan_tambahan' => $gajiunivRequest-> tunjangan_tambahan,
            'tunjangan_suami_istri' => $gajiunivRequest-> tunjangan_suami_istri,
            'tunjangan_anak' => $gajiunivRequest-> tunjangan_anak,
            'uang_lembur_hk' => $gajiunivRequest-> uang_lembur_hk,
            'uang_lembur_hl' => $gajiunivRequest-> uang_lembur_hl,
            'transport_kehadiran' => $gajiunivRequest-> transport_kehadiran,
            'honor_universitas' => $gajiunivRequest-> honor_universitas,
        ]);
        }

         // Update Gaji Fakultas
         $gajifak = $mastertransaksi->gaji_fakultas;
         if ($gajifak) {
            $gajifak->update([
                'gaji_fakultas' => json_encode($gajifakRequest->gaji_fakultas)
            ]);
        // Decode "gaji_fakultas" data before sending the response
        $gajifak->gaji_fakultas = json_decode($gajifak->gaji_fakultas);
        }

        // Update Potongan
        $potongan = $mastertransaksi->potongan;
        if ($potongan) {
            $potongan->update([
                'potongan' => json_encode($potonganRequest->potongan)
            ]);
        // Decode "potongan" data before sending the response
        $potongan->potongan = json_decode($potongan->potongan);
        }

         // Update Pajak
         $pajak = $mastertransaksi->pajak;
         if ($pajak) {
             $pajak->update([
                'pensiun' => $pajakRequest-> pensiun,
                'bruto_pajak' =>  $pajakRequest->bruto_pajak,
                'bruto_murni' =>  $pajakRequest->bruto_murni,
                'biaya_jabatan' =>  $pajakRequest->biaya_jabatan,
                'aksa_mandiri' => $pajakRequest-> aksa_mandiri,
                'dplk_pensiun' => $pajakRequest-> dplk_pensiun,
                'jumlah_potongan_kena_pajak' =>  $pajakRequest->jumlah_potongan_kena_pajak,
                'jumlah_set_potongan_kena_pajak' =>  $pajakRequest->jumlah_set_potongan_kena_pajak,
                'ptkp' => $pajakRequest-> ptkp,
                'pkp' =>  $pajakRequest->pkp,
                'pajak_pph21' =>  $pajakRequest->pajak_pph21,
                'jumlah_set_pajak' =>  $pajakRequest->jumlah_set_pajak,
                'potongan_tak_kena_pajak' =>  $pajakRequest->potongan_tak_kena_pajak,
                'pendapatan_bersih' =>  $pajakRequest->pendapatan_bersih,
               ]);
         }

       // Update Master Transaksi
       $mastertransaksi->update([
       'karyawan_id'=> $mastertransaksi->karyawan_id, //id yg sama
       'karyawan_bank_id'=>  $transaksiRequest->karyawan_bank_id,
       'status_bank' => $transaksiRequest-> status_bank,
       'gaji_date_start' => $mastertransaksi->gaji_date_start, //data yg sama
       'gaji_date_end' => $mastertransaksi->gaji_date_end, //data yg sama
       'karyawan_gaji_universitas_id'=> $mastertransaksi->karyawan_gaji_universitas_id, //id yg sama
       'karyawan_gaji_fakultas_id'=> $mastertransaksi->karyawan_gaji_fakultas_id, //id yg sama
       'karyawan_potongan_id'=>$mastertransaksi->karyawan_potongan_id, //id yg sama
       'karyawan_pajak_id'=>$mastertransaksi->karyawan_pajak_id //id yg sama
       ]);

   // Commit transaksi jika berhasil
    DB::commit();
} else {
    // Handle jika master transaksi dengan ID $id tidak ditemukan
    return ResponseFormatter::error('Master Transaksi not found', 404);
}
    return ResponseFormatter::success([
        'transaksi' => $mastertransaksi,
    ],'Data Transaksi Gaji Karyawan updated');
   }
   catch(Exception $e){
       DB::rollback();
       return ResponseFormatter::error($e->getMessage(), 500);
   }
}

  //  Hapus Transaksi Gaji Karyawan
  public function destroy($transaksiId){
    try{
       // Get Master Transaksi
    $masterTransaksi = Karyawan_Master_Transaksi::find($transaksiId);
    // Check if Dosen Luar Biasa exists
        if(!$masterTransaksi){
            return ResponseFormatter::error('Data Transaksi Gaji Karyawan not found',404);
        }

    // Hapus gaji universitas, gaji fakultas, potongan, dan pajak berdasarkan kriteria
    $masterTransaksi->gaji_universitas()->delete();
    $masterTransaksi->gaji_fakultas()->delete();
    $masterTransaksi->potongan()->delete();
    $masterTransaksi->pajak()->delete();

    // Hapus Master Transaksi
    $masterTransaksi->delete();

        return ResponseFormatter::success('Data Transaksi Gaji Karyawan deleted');

    }catch(Exception $e){
        return ResponseFormatter::error($e->getMessage(), 500);
    }
     }
}
